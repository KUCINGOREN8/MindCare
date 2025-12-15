<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Psychologist;
use App\Models\Appointment;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookAppointmentController extends Controller
{
    public function showBook(?Psychologist $psychologist = null)
    {
        $user = Auth::user();
        if (!$user->otp_verified || $user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Please verify your email first or contact admin.');
        }

        if ($psychologist) {
            if (!$psychologist->user || !$psychologist->user->otp_verified || $psychologist->user->status !== 'active') {
                abort(404, 'Psychologist not found or not active');
            }

            $psychologists = collect([$psychologist->load('user', 'schedules')]);
            $isSpecific = true;
        } else {
            $psychologists = Psychologist::with(['user', 'schedules'])
                ->whereHas('user', function($q) {
                    $q->where('otp_verified', true)->where('status', 'active');
                })
                ->get();
            $isSpecific = false;
        }

        return view('patient.appointment.book-appointment', compact('psychologists', 'isSpecific'));
    }

     public function store(Request $request)
    {
        $psychologist = Psychologist::with('user')->find($request->psychologist_id);

        if (!$psychologist || !$psychologist->user || !$psychologist->user->otp_verified || $psychologist->user->status !== 'active') {
            return back()->with('error', 'Cannot book appointment with this psychologist.');
        }

        $appointmentDateTime = Carbon::parse($request->date . ' ' . $request->start_time);
        if ($appointmentDateTime->lte(now())) {
            return back()->with('error', 'Cannot book past appointment time.');
        }

        $existingAppointment = Appointment::where('psychologist_id', $request->psychologist_id)
            ->whereDate('date', $request->date)
            ->where('start_time', $request->start_time)
            ->whereIn('status', ['pending_payment', 'pending', 'confirmed'])
            ->first();

        if ($existingAppointment) {
            return back()->with('error', 'This time slot is already booked. Please choose another time.');
        }

        $dayOfWeek = strtolower(Carbon::parse($request->date)->format('l'));
        $schedule = $psychologist->schedules()->where('day_of_week', $dayOfWeek)->first();

        $startTime = strtotime($request->start_time);
        $scheduleStart = strtotime($schedule->start_time);
        $scheduleEnd = strtotime($schedule->end_time);
        $sessionDuration = 5400;

        if ($startTime < $scheduleStart || ($startTime + $sessionDuration) > $scheduleEnd) {
            return back()->with('error', 'Selected time is outside psychologist working hours.');
        }

        if (!$schedule) {
            return back()->with('error', 'Psychologist is not available on this day.');
        }

        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'psychologist_id' => $request->psychologist_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'consultation_fee' => $request->consultation_fee,
            'with' => $request->with,
            'job_title' => $request->job_title,
            'status' => 'pending_payment',
        ]);

        $midtransService = new MidtransService();
        $orderId = $midtransService->generateOrderId();

        $payment = Payment::create([
            'paymentable_id' => $appointment->id,
            'paymentable_type' => Appointment::class,
            'order_id' => $orderId,
            'amount' => $request->consultation_fee,
            'status' => 'pending',
        ]);

        $customerDetails = [
            'first_name' => Auth::user()->full_name,
            'email' => Auth::user()->email,
        ];

        try {
            $transaction = $midtransService->createTransaction(
                $orderId,
                $request->consultation_fee,
                $customerDetails
            );

            $payment->update([
                'payment_url' => $transaction['redirect_url']
            ]);

            return redirect()->route('patient.payment.process', $payment);

        } catch (\Exception $e) {
            $appointment->delete();
            $payment->delete();

            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    public function getAvailableDays(Psychologist $psychologist)
    {
        $availableDays = $psychologist->schedules()
            ->pluck('day_of_week')
            ->unique()
            ->values()
            ->toArray();

        return response()->json($availableDays);
    }


    public function getAvailableDates(Psychologist $psychologist)
    {
        if (!$psychologist->user || !$psychologist->user->otp_verified || $psychologist->user->status !== 'active') {
            return response()->json([
                'error' => 'Psychologist not available',
                'message' => 'This psychologist is not currently available for booking.'
            ]);
        }

        $availableDates = [];
        $schedules = $psychologist->schedules;

        for ($i = 1; $i <= 30; $i++) {
            $date = now()->addDays($i);
            $dayOfWeek = strtolower($date->format('l'));

            if ($schedules->where('day_of_week', $dayOfWeek)->count() > 0) {
                $availableDates[] = $date->format('Y-m-d');
            }
        }
        return response()->json($availableDates);
    }

    public function getAvailableTimes(Psychologist $psychologist, Request $request)
    {
        if (!$psychologist->user || !$psychologist->user->otp_verified || $psychologist->user->status !== 'active') {
            return response()->json([
                'error' => 'Psychologist not available',
                'message' => 'This psychologist is not currently available for booking.'
            ]);
        }

        $request->validate([
            'date' => 'required|date'
        ]);

        $date = $request->date;
        $now = now();

        if (Carbon::parse($date)->lt($now->startOfDay())) {
            return response()->json([]);
        }

        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        $schedule = $psychologist->schedules()->where('day_of_week', $dayOfWeek)->first();

        if (!$schedule) {
            return response()->json([]);
        }

        $bookedTimes = Appointment::where('psychologist_id', $psychologist->id)
            ->whereDate('date', $date)
            ->whereIn('status', ['pending_payment', 'pending', 'confirmed'])
            ->pluck('start_time')
            ->toArray();

        $availableTimes = [];
        $start = strtotime($schedule->start_time);
        $end = strtotime($schedule->end_time);

        $sessionDuration = 5400;

        for ($time = $start; $time <= $end - $sessionDuration; $time += 3600) {
            $timeStr = date('H:i', $time);

            $selectedDateTime = Carbon::parse($date . ' ' . $timeStr);
            if ($selectedDateTime->lte($now)) {
                continue;
            }

            $isAvailable = true;
            $sessionEnd = $time + $sessionDuration;

            foreach ($bookedTimes as $booked) {
                $bookedStart = strtotime($booked);
                $bookedEnd = $bookedStart + $sessionDuration;

                if ($time < $bookedEnd && $sessionEnd > $bookedStart) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $availableTimes[] = $timeStr;
            }
        }

        return response()->json($availableTimes);
    }

    public function getAvailablePsychologists(Request $request)
    {
        try {
            $date = $request->date;
            $time = $request->time;

            if (!$date || !$time) {
                return response()->json([]);
            }

            $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));

            $allPsychologists = Psychologist::with(['user', 'schedules'])
                ->whereHas('user', function($q) {
                    $q->where('otp_verified', true)->where('status', 'active');
                })
                ->get();

            $availablePsychologists = $allPsychologists->filter(function($psychologist) use ($date, $time, $dayOfWeek) {
                $schedule = $psychologist->schedules->first(function($schedule) use ($dayOfWeek) {
                    return strtolower($schedule->day_of_week) === $dayOfWeek;
                });

                if (!$schedule) {
                    return false;
                }
                $selectedTime = strtotime($time);
                $scheduleStart = strtotime($schedule->start_time);
                $scheduleEnd = strtotime($schedule->end_time);
                $sessionDuration = 5400;

                if ($selectedTime < $scheduleStart || ($selectedTime + $sessionDuration) > $scheduleEnd) {
                    return false;
                }

                $hasAppointment = $psychologist->appointments()
                    ->whereDate('date', $date)
                    ->where('start_time', $time)
                    ->whereIn('status', ['pending_payment', 'pending', 'confirmed'])
                    ->exists();

                return !$hasAppointment;
            })
            ->map(function($psychologist) {
                return [
                    'id' => $psychologist->id,
                    'user' => [
                        'full_name' => $psychologist->user->full_name,
                        'gender' => $psychologist->user->gender,
                        'photo_url' => $psychologist->user->photo_url,
                    ],
                    'title' => $psychologist->title,
                    'consultation_fee' => $psychologist->consultation_fee,
                ];
            });

            return response()->json($availablePsychologists->values());

        } catch (\Exception $e) {
            Log::error('Error in getAvailablePsychologists: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Psychologist;
use App\Models\Appointment;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


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

        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        $schedule = $psychologist->schedules()->where('day_of_week', $dayOfWeek)->first();

        if (!$schedule) {
            return response()->json([]);
        }

        $bookedTimes = Appointment::where('psychologist_id', $psychologist->id)
            ->whereDate('date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('start_time')
            ->toArray();

        $availableTimes = [];
        $start = strtotime($schedule->start_time);
        $end = strtotime($schedule->end_time);

        $sessionDuration = 5400;

        for ($time = $start; $time <= $end - $sessionDuration; $time += 3600) {
            $timeStr = date('H:i', $time);

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
        $date = $request->date;
        $time = $request->time;

        $psychologists = Psychologist::with(['user', 'schedules'])
            ->whereHas('user', function($q) {
                $q->where('otp_verified', true)
                ->where('status', 'active');
            })
            ->whereHas('schedules', function($q) use ($date) {
                $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
                $q->where('day_of_week', $dayOfWeek);
            })
            ->whereDoesntHave('appointments', function($q) use ($date, $time) {
                $q->whereDate('date', $date)
                ->where('start_time', $time)
                ->where('status', '!=', 'cancelled');
            })
            ->get();

        return response()->json($psychologists);
    }
}

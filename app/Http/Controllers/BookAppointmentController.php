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
        if ($psychologist) {
            $psychologists = collect([$psychologist->load('user', 'schedules')]);
            $isSpecific = true;
        } else {
            $psychologists = Psychologist::with(['user', 'schedules'])
                ->whereHas('user', function($q) {
                    $q->where('otp_verified', true);
                })
                ->get();
            $isSpecific = false;
        }

        return view('patient.appointment.book-appointment', compact('psychologists', 'isSpecific'));
    }

     public function store(Request $request)
    {
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

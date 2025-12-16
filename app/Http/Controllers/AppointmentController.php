<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $this->autoCompletePastAppointments($user->id);
        $this->autoUpdateExpiredPayments($user->id);

        // UPCOMING
        $upcomingAppointments = Appointment::with('psychologist.user')
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->where(function ($query) {
                $query->whereDate('date', '>', now())
                    ->orWhere(function ($q) {
                        $q->whereDate('date', now())
                            ->whereTime('end_time', '>', now());
                    });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $upcomingIds = $upcomingAppointments->pluck('id')->toArray();

        // HISTORY
        $history = Appointment::with('psychologist.user')
            ->where('user_id', $user->id)
            ->whereNotIn('id', $upcomingIds)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view(
            'patient.appointment.appointments',
            compact('upcomingAppointments', 'history', 'user')
        );
    }

    private function autoCompletePastAppointments($userId)
    {
        $appointments = Appointment::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->past()
            ->get();

        foreach ($appointments as $appointment) {
            $appointment->markAsCompleted();
        }

        return $appointments->count();
    }

    private function autoUpdateExpiredPayments($userId)
    {
        $appointments = Appointment::where('user_id', $userId)
            ->where('status', 'pending_payment')
            ->get();

        foreach ($appointments as $appointment) {
            $payment = Payment::where('paymentable_id', $appointment->id)
                ->where('paymentable_type', Appointment::class)
                ->where('status', 'pending')
                ->first();

            if (!$payment) {
                continue;
            }

            $expiryTime = $payment->expiry_at ?? $payment->created_at->addMinutes(15);

            if (now()->greaterThan($expiryTime)) {
                $appointment->markAsExpired();
            }
        }
    }

    public function confirm($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'confirmed';
        $appointment->save();
        return back();
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'cancelled';
        $appointment->save();
        return back();
    }

    public function showPaymentPage(Appointment $appointment)
    {
        if (auth()->id() !== $appointment->user_id) {
            abort(403);
        }

        if ($appointment->status !== 'pending_payment') {
            return redirect()->route('patient.appointments.index');
        }

        $payment = Payment::where('paymentable_id', $appointment->id)
            ->where('paymentable_type', Appointment::class)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            return redirect()->route('patient.appointments.index')->with('error', 'Payment session expired. Please book a new appointment.');
        }

        if ($payment->status === 'expired') {
            return redirect()->route('patient.appointments.index')->with('error', 'Payment time has expired. Please book a new appointment.');
        }

        $expiryTime = $payment->expiry_at ?? $payment->created_at->addMinutes(15);

        if (now()->greaterThan($expiryTime)) {
            $appointment->markAsExpired();
            return redirect()->route('patient.appointments.index')->with('error', 'Payment time has expired. Please book a new appointment.');
        }

        $remainingMinutes = now()->diffInMinutes($expiryTime, false);
        $remainingMinutes = max(1, $remainingMinutes);

        return $this->redirectToMidtrans($payment, $remainingMinutes);
    }

    private function redirectToMidtrans(Payment $payment, $remainingMinutes)
    {
        $midtransService = new MidtransService();

        $expiryDuration = (int) max(1, ceil($remainingMinutes));

        $params = [
            'transaction_details' => [
                'order_id' => $payment->order_id,
                'gross_amount' => (int) $payment->amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->full_name,
                'email' => auth()->user()->email,
            ],
            'expiry' => [
                'duration' => $expiryDuration,
                'unit' => 'minute'
            ],
        ];

        try {
            $snapToken = $midtransService->createPaymentWithExpiry(
                $payment->order_id,
                (int) $payment->amount,
                $expiryDuration
            );

            return view('patient.payment.redirect', [
                'snapToken' => $snapToken,
                'finishUrl' => route('patient.payment.finish'),
                'errorUrl' => route('patient.payment.error'),
                'appointmentsUrl' => route('patient.appointments.index')
            ]);

        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']);

            return redirect()->route('patient.appointments.index')->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'reschedule_date' => 'required|date|after_or_equal:today',
            'reschedule_time' => 'required',
        ]);

        $appointment = Appointment::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed appointments can be rescheduled.');
        }

        if ($appointment->is_past || $appointment->is_ongoing) {
            return back()->with('error', 'Cannot reschedule past or ongoing appointments.');
        }

        $newDateTime = Carbon::parse($request->reschedule_date . ' ' . $request->reschedule_time);
        if ($newDateTime->lte(now())) {
            return back()->with('error', 'Cannot reschedule to past time.');
        }

        $psychologist = $appointment->psychologist;
        if (!$psychologist->user || !$psychologist->user->otp_verified || $psychologist->user->status !== 'active') {
            return back()->with('error', 'Psychologist is not available for rescheduling.');
        }

        $existingAppointment = Appointment::where('psychologist_id', $psychologist->id)
            ->whereDate('date', $request->reschedule_date)
            ->where('start_time', $request->reschedule_time)
            ->whereIn('status', ['pending_payment', 'pending', 'confirmed'])
            ->where('id', '!=', $appointment->id)
            ->first();

        if ($existingAppointment) {
            return back()->with('error', 'This time slot is already booked. Please choose another time.');
        }

        $dayOfWeek = strtolower(Carbon::parse($request->reschedule_date)->format('l'));
        $schedule = $psychologist->schedules()->where('day_of_week', $dayOfWeek)->first();

        if (!$schedule) {
            return back()->with('error', 'Psychologist is not available on selected day.');
        }

        $startTime = strtotime($request->reschedule_time);
        $scheduleStart = strtotime($schedule->start_time);
        $scheduleEnd = strtotime($schedule->end_time);
        $sessionDuration = 5400;

        if ($startTime < $scheduleStart || ($startTime + $sessionDuration) > $scheduleEnd) {
            return back()->with('error', 'Selected time is outside psychologist working hours.');
        }

        $appointment->update([
            'date'       => $request->reschedule_date,
            'start_time' => $request->reschedule_time,
            'end_time'   => date('H:i', $startTime + $sessionDuration),
            'status'     => 'confirmed',
            'notes'      => $appointment->notes . ' (Rescheduled on ' . now()->format('Y-m-d H:i') . ')'
        ]);

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment successfully rescheduled.');
    }

    public function getRescheduleTimes($id, Request $request)
    {
        $appointment = Appointment::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $psychologist = $appointment->psychologist;
        $date = $request->date;

        if (!$date) {
            return response()->json([]);
        }

        if (!$psychologist->user || !$psychologist->user->otp_verified || $psychologist->user->status !== 'active') {
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
            ->where('id', '!=', $appointment->id)
            ->pluck('start_time')
            ->toArray();

        $availableTimes = [];
        $start = strtotime($schedule->start_time);
        $end = strtotime($schedule->end_time);
        $sessionDuration = 5400;

        $isToday = Carbon::parse($date)->isToday();

        for ($time = $start; $time <= $end - $sessionDuration; $time += 3600) {
            $timeStr = date('H:i', $time);

            if ($isToday) {
                $selectedDateTime = Carbon::parse($date . ' ' . $timeStr);
                if ($selectedDateTime->lte(now())) {
                    continue;
                }
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

    public function psychologistAppointments()
    {
        $psychologist = Auth::user()->psychologist;

        $upcomingAppointments = $psychologist->appointments()
            ->with('user')
            ->where('status', 'confirmed')
            ->where(function($q) {
                $q->whereDate('date', '>', now())
                    ->orWhere(function($q2) {
                        $q2->whereDate('date', '=', now())
                            ->whereTime('end_time', '>', now()->format('H:i:s'));
                    });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $todayAppointments = $psychologist->appointments()
            ->with('user')
            ->whereDate('date', now())
            ->where('status', 'confirmed')
            ->orderBy('start_time')
            ->get();

        $pendingAppointments = $psychologist->appointments()
            ->with('user')
            ->where('status', 'pending_payment')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('psychologist.appointment.index', compact(
            'upcomingAppointments',
            'todayAppointments',
            'pendingAppointments'
        ));
    }
}

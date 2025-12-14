<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $this->autoCompletePastAppointments($user->id);
        $this->autoUpdateExpiredPayments($user->id);

        $upcomingAppointments = Appointment::with(['psychologist' => function($query) {
                $query->with('user');
            }])
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->where(function($query) {
                $query->whereDate('date', '>', now()->format('Y-m-d'))
                    ->orWhere(function($q) {
                        $q->whereDate('date', '=', now()->format('Y-m-d'))
                            ->whereTime('end_time', '>', now()->format('H:i:s'));
                    });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $upcomingIds = $upcomingAppointments->pluck('id')->toArray();

        $history = Appointment::with(['psychologist' => function($query) {
                $query->with('user');
            }])
            ->where('user_id', $user->id)
            ->whereNotIn('id', $upcomingIds)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        $rescheduleRequests = Appointment::with(['psychologist' => function($query) {
                $query->with('user');
            }])
            ->where('user_id', $user->id)
            ->whereNotNull('reschedule_date')
            ->where('status', '!=', 'cancelled')
            ->orderBy('reschedule_date')
            ->orderBy('reschedule_time')
            ->get();

        return view('patient.appointment.appointments', compact('upcomingAppointments', 'history', 'rescheduleRequests', 'user'));
    }


    private function autoCompletePastAppointments($userId)
    {
       $appointments = Appointment::where('user_id', $userId)->where('status', 'confirmed')->get();

       foreach ($appointments as $appointment) {
        if ($appointment->is_past) {
            $appointment->update(['status' => 'completed']);
        }
        }
       return $appointments->where('is_past', true)->count();
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
                $payment->update(['status' => 'expired']);
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
        $appointment->status = 'canceled';
        $appointment->save();
        return back();
    }

    // public function reschedule(Request $request, $id)
    // {
    //     $appointment = Appointment::findOrFail($id);

    //     $appointment->reschedule_date = $request->reschedule_date;
    //     $appointment->reschedule_time = $request->reschedule_time;
    //     $appointment->reschedule_reason = $request->reschedule_reason;

    //     $appointment->save();

    //     return view('profile.appointments', compact('ongoing', 'history'));
    // }

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
            $payment->update(['status' => 'expired']);
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

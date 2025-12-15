<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\PsychologistSchedule;
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

        $upcomingAppointments = Appointment::with('psychologist.user', 'psychologist.schedules') 
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            // Hapus whereNull('reschedule_time') di sini
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

        
        $rescheduleRequests = Appointment::with('psychologist.user')
            ->where('user_id', $user->id)
            ->whereNotNull('reschedule_time')
            ->orderBy('reschedule_date', 'asc')
            ->get();
        
        
        $history = Appointment::with('psychologist.user')
            ->where('user_id', $user->id)
            ->whereNotIn('id', $upcomingIds)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view(
            'patient.appointment.appointments',
            compact('upcomingAppointments', 'history', 'user', 'rescheduleRequests')
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
            'reschedule_time' => 'required|date_format:H:i',
            'reschedule_reason' => 'nullable|string|max:255',
        ]);

        $appointment = Appointment::where('id', $id)
            ->with('psychologist')
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($appointment->status === 'completed' || $appointment->status === 'cancelled') {
            return back()->with('error', 'Appointment cannot be rescheduled.');
        }
        
        // Simpan waktu aktif lama sebelum ditimpa
        $oldDate = Carbon::parse($appointment->date)->format('D, d M Y');
        $oldTime = Carbon::parse($appointment->start_time)->format('H:i');
        $oldDateTimeString = "Original: {$oldDate} at {$oldTime}"; 

        $newDate = Carbon::parse($request->reschedule_date);
        $newDayOfWeek = strtolower($newDate->format('l')); 

        $newStartTime = $request->reschedule_time;
        $newEndTime = Carbon::createFromFormat('H:i', $newStartTime)->addMinutes(60)->format('H:i:s'); 
        
        // --- Validasi Ketersediaan Jadwal Psikolog ---
        $isAvailable = PsychologistSchedule::where('psychologist_id', $appointment->psychologist_id)
            ->whereRaw("LOWER(day_of_week) = ?", [$newDayOfWeek])
            ->where('start_time', '<=', $newStartTime)
            ->where('end_time', '>=', $newEndTime)
            ->exists();

        if (!$isAvailable) {
            return back()->with('error', 'The selected time is outside the psychologist\'s registered working hours for that day.');
        }
        
        // --- Validasi Bentrok Booking Lain ---
        $isBooked = Appointment::where('psychologist_id', $appointment->psychologist_id)
            ->where('date', $newDate->toDateString())
            ->where('start_time', '<=', $newStartTime)
            ->where('end_time', '>', $newStartTime)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->where('id', '!=', $appointment->id) 
            ->exists();
            
        if ($isBooked) {
            return back()->with('error', 'The selected time slot is already booked by another patient.');
        }
        
        // Buat riwayat reschedule baru untuk disimpan
        $rescheduleReasonHistory = "Rescheduled from [{$oldDateTimeString}] because: {$request->reschedule_reason}";

        
        $appointment->update([
            'date'              => $newDate->toDateString(),    
            'start_time'        => $newStartTime,               
            'end_time'          => $newEndTime,                 
            
            'reschedule_date'   => $newDate->toDateString(),                      
            'reschedule_time'   => $newStartTime,                        
            'reschedule_reason' => $rescheduleReasonHistory, 
            
            'status'            => 'confirmed',                
        ]);

        return redirect()
            ->route('patient.appointments.index')
            ->with('success', 'Appointment has been successfully rescheduled.');
    }

        public function psychologistAppointments()
    {
        $psychologist = Auth::user()->psychologist;

        $upcomingAppointments = $psychologist->appointments()
            ->with('user')
            ->where('status', 'confirmed')
            ->whereNull('reschedule_time') // Hanya yang tidak ada permintaan reschedule aktif
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
            ->whereNull('reschedule_time') // Hanya yang tidak ada permintaan reschedule aktif
            ->orderBy('start_time')
            ->get();

        $pendingAppointments = $psychologist->appointments()
            ->with('user')
            ->where('status', 'pending_payment')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // RESCHEDULE REQUESTS: Hanya cek reschedule_time
        $rescheduleRequests = $psychologist->appointments()
            ->with('user')
            ->whereNotNull('reschedule_time')
            ->orderBy('reschedule_date')
            ->get();

        return view('psychologist.appointment.index', compact(
            'upcomingAppointments',
            'todayAppointments',
            'pendingAppointments',
            'rescheduleRequests'
        ));
    }
}

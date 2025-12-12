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

        if (strtolower(trim($user->role)) === 'psychologist') {
            
            // Ambil Data Next Client
            $upcoming = Appointment::with('user')
                ->where('psychologist_id', $user->id) 
                ->where('status', 'confirmed')
                ->where(function($query) {
                    $query->whereDate('date', '>', now())
                          ->orWhere(function($q) {
                              $q->whereDate('date', '=', now())
                                ->whereTime('start_time', '>', now()->format('H:i:s')); 
                          });
                })
                ->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc') 
                ->get(); 

            // Ambil Data History
            $history = Appointment::with('user')
                ->where('psychologist_id', $user->id)
                ->where(function($query) {
                    $query->whereIn('status', ['completed', 'cancelled', 'canceled']) 
                          ->orWhereDate('date', '<', now());
                })
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc') 
                ->get();

            // RETURN DI SINI (Agar berhenti dan tidak baca kode Pasien di bawah)
            return view('psychologist.appointment.index', compact('upcoming', 'history'));
        }

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


    public function reschedule(Request $request, $id)
{
    $user = Auth::user();

    //hanya bs ganti tanggal dan waktu
    $request->validate([
        'reschedule_date' => 'required|date',
        'reschedule_time' => 'required',
    ]);
    $appointment = Appointment::where('id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();



    // status
    if (in_array($appointment->status, ['completed', 'cancelled', 'canceled'])) {
        return back()->with('error', 'Gagal. Sesi yang sudah selesai atau dibatalkan tidak dapat diubah.');
    }

    
    $currentSessionTime = Carbon::parse($appointment->date . ' ' . $appointment->start_time);

    //cek sesi uda mulai apa belum
    if (now()->greaterThanOrEqualTo($currentSessionTime)) {
        return back()->with('error', 'This session has started/finished.');
    }

    //validasi tanggal

    $targetSessionTime = Carbon::parse($request->reschedule_date . ' ' . $request->reschedule_time);

    if ($targetSessionTime->isPast()) {
        return back()->with('error', 'Invalid');
    }

    
    //Cek jadwal	
    
    $isSlotTaken = Appointment::where('psychologist_id', $appointment->psychologist_id)
        ->where('date', $request->reschedule_date)
        ->where('start_time', $request->reschedule_time)
        ->where('status', 'confirmed') 
        ->where('id', '!=', $id)       
        ->exists();

    if ($isSlotTaken) {

    }        return back()->with('error', 'This time slot is no longer available.');

  

    $appointment->update([
        'date' => $request->reschedule_date,      
        'start_time' => $request->reschedule_time, 
      
        'reschedule_date' => null,
        'reschedule_time' => null,
        'reschedule_reason' => null,
    ]);

    return back()->with('success', 'Jadwal berhasil diubah ke tanggal ' . $targetSessionTime->format('d M Y H:i'));
}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Psychologist;
use App\Models\Appointment;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;


class BookAppointmentController extends Controller
{
    public function showBook()
    {

        $psychologists = Psychologist::with('user')->get();

        return view('patient.appointment.book-appointment', compact('psychologists'));

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

    public function showAvailableTimes(Request $request)
    {
        $psychologistId = $request->input('psychologist_id');
        $date = $request->input('date');

        $availableTimes = [
            '09:00', '09:30', '10:00', '10:30',
            '11:00', '13:00', '14:00', '15:00'
        ];

        return response()->json($availableTimes);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function process(Payment $payment)
    {
        if (!$payment->payment_url) {
            return redirect()->route('payment.error')
                ->with('error', 'Payment URL not found');
        }

        return redirect($payment->payment_url);
    }

    public function finish(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$orderId) {
            return view('pages.payment.status', [
                'status' => 'error',
                'message' => 'Order ID not found'
            ]);
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return view('pages.payment.status', [
                'status' => 'error',
                'message' => 'Payment not found'
            ]);
        }

        return view('pages.payment.status', [
            'status' => $payment->status,
            'payment' => $payment,
            'message' => $this->getStatusMessage($payment->status)
        ]);
    }

    public function error(Request $request)
    {
        return view('pages.payment.status', [
            'status' => 'error',
            'message' => 'Payment failed or was cancelled'
        ]);
    }

    public function pending(Request $request)
    {
        return view('pages.payment.status', [
            'status' => 'pending',
            'message' => 'Payment is pending. Please complete your payment.'
        ]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Webhook Received:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->update([
            'transaction_id' => $payload['transaction_id'] ?? null,
            'payment_type' => $payload['payment_type'] ?? null,
            'va_number' => $payload['va_number'] ?? null,
            'payload' => $payload,
            'status' => $this->mapMidtransStatus($transactionStatus, $fraudStatus),
        ]);

        if ($payment->status === 'success') {
            $appointment = $payment->paymentable;
            if ($appointment instanceof Appointment) {
                $appointment->update(['status' => 'confirmed']);
            }
        }

        return response()->json(['message' => 'Webhook processed']);
    }

    private function mapMidtransStatus($transactionStatus, $fraudStatus)
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'challenge' ? 'pending' : 'success';
        }

        if ($transactionStatus === 'settlement') {
            return 'success';
        }

        if ($transactionStatus === 'pending') {
            return 'pending';
        }

        if ($transactionStatus === 'deny' || $transactionStatus === 'cancel' || $transactionStatus === 'expire') {
            return 'failed';
        }

        return 'pending';
    }

    private function getStatusMessage($status)
    {
        $messages = [
            'success' => 'Payment successful! Your appointment is confirmed.',
            'pending' => 'Payment is pending. Please complete your payment.',
            'failed' => 'Payment failed. Please try again.',
            'expired' => 'Payment expired. Please book again.',
            'canceled' => 'Payment was cancelled.',
        ];

        return $messages[$status] ?? 'Payment status unknown.';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


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

        if (config('services.midtrans.is_production') === false && $payment->status === 'pending') {
            $actualStatus = $this->verifyWithMidtrans($orderId);
            if ($actualStatus && $actualStatus !== 'pending') {
                $payment->update(['status' => $actualStatus]);

                if ($actualStatus === 'success') {
                    $appointment = $payment->paymentable;
                    if ($appointment instanceof Appointment) {
                        $appointment->update(['status' => 'confirmed']);
                    }
                }

                $payment->refresh();
            }
        }

        return view('pages.payment.status', [
            'status' => $payment->status,
            'payment' => $payment,
            'message' => $this->getStatusMessage($payment->status)
        ]);
    }

    private function verifyWithMidtrans($orderId)
    {
        if (config('services.midtrans.is_production') === true) {
            return null;
        }

        try {
            $serverKey = config('services.midtrans.server_key');

            $response = Http::withBasicAuth($serverKey, '')
                ->acceptJson()
                ->get('https://api.sandbox.midtrans.com/v2/' . $orderId . '/status');

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['transaction_status'])) {
                    return $this->mapMidtransStatus(
                        $data['transaction_status'],
                        $data['fraud_status'] ?? 'accept'
                    );
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
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

    private function isValidSignature($request)
    {
        $serverKey = config('services.midtrans.server_key');
        $signatureKey = $request->header('x-midtrans-signature') ?? '';
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;

        $hash = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($hash, $signatureKey);
    }

   public function webhook(Request $request)
    {
        $payload = $request->all();

        if (!$this->isValidSignature($request)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? 'accept';

        if (!$orderId || !$transactionStatus) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $newStatus = $this->mapMidtransStatus($transactionStatus, $fraudStatus);

        $payment->update([
            'transaction_id' => $payload['transaction_id'] ?? null,
            'payment_type' => $payload['payment_type'] ?? null,
            'va_number' => $payload['va_number'] ?? null,
            'payload' => $payload,
            'status' => $newStatus,
        ]);

        if ($newStatus === 'success') {
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
            return ($fraudStatus === 'challenge' || $fraudStatus === 'deny') ? 'pending' : 'success';
        }

        if ($transactionStatus === 'settlement') {
            return 'success';
        }

        if ($transactionStatus === 'pending') {
            return 'pending';
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
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

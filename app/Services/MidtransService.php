<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction($orderId, $amount, $customerDetails)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $amount,
            ],
            'customer_details' => $customerDetails,
            'enabled_payments' => [
                'gopay', 'shopeepay', 'bank_transfer',
                'qris', 'credit_card', 'bca_klikbca'
            ],
            'callbacks' => [
                'finish' => route('patient.payment.finish'),
                'error' => route('patient.payment.error'),
                'pending' => route('patient.payment.pending'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $snapUrl = Snap::getSnapUrl($params);

            return [
                'token' => $snapToken,
                'redirect_url' => $snapUrl
            ];
        } catch (Exception $e) {
            throw new Exception('Midtrans Error: ' . $e->getMessage());
        }
    }

    public function generateOrderId()
    {
        return 'APP-' . date('YmdHis') . '-' . strtoupper(uniqid());
    }
}

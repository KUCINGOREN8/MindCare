<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // KONFIGURASI MIDTRANS
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$clientKey = config('services.midtrans.client_key');

        // FIX SSL certificate error (WAJIB DI XAMPP WINDOWS)
        Config::$curlOptions = [
            CURLOPT_CAINFO => base_path('cacert.pem'),
            CURLOPT_CAPATH => base_path('cacert.pem'),
        ];
    }

    // Generate Order ID unik untuk setiap transaksi
    public function generateOrderId()
    {
        return 'ORDER-' . time() . '-' . rand(1000, 9999);
    }

    // Membuat transaksi Midtrans (Snap)
    public function createTransaction($orderId, $amount, $customerDetails)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => $customerDetails
        ];

        return Snap::createTransaction($params);
    }
}

<?php

return [
    'isProduction' => env('MIDTRANS_IS_PRODUCTION', false),
    'serverKey' => env('MIDTRANS_SERVER_KEY'),
    'clientKey' => env('MIDTRANS_CLIENT_KEY'),

    'curlOptions' => [
        CURLOPT_CAINFO => base_path('cacert.pem'),
        CURLOPT_CAPATH => base_path('cacert.pem'),
    ],
];

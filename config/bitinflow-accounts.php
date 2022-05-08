<?php

return [
    'client_id' => env('BITINFLOW_ACCOUNTS_KEY'),
    'client_secret' => env('BITINFLOW_ACCOUNTS_SECRET'),
    'redirect_url' => env('BITINFLOW_ACCOUNTS_REDIRECT_URI'),
    'base_url' => env('BITINFLOW_ACCOUNTS_BASE_URL'),
    'payments' => [
        'base_url' => env('BITINFLOW_PAYMENTS_BASE_URL', 'https://api.pay.bitinflow.com/v1/'),
        'dashboard_url' => env('BITINFLOW_PAYMENTS_DASHBOARD_URL', 'https://pay.bitinflow.com/v1/'),
    ]
];

<?php

return [
    'api_version'       => env('WPP_API_VERSION', 'v19.0'),
    'business_id'       => env('WPP_BUSINESS_ID'),
    'business_phone_id' => env('WPP_BUSINESS_PHONE_ID'),
    'server_url'        => env('WPP_SERVER_URL', 'https://graph.facebook.com'),
    'token'             => env('WPP_TOKEN'),
    'webhook_verify'    => env('WPP_WEBHOOK_VERIFY'),
];

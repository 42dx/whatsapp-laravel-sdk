<?php

return [
    'api_version'       => env('WPP_API_VERSION', 'v20.0'),
    'business_id'       => env('WPP_BUSINESS_ID'),
    'business_phone_id' => env('WPP_BUSINESS_PHONE_ID'),
    'server_url'        => env('WPP_SERVER_URL', 'https://graph.facebook.com'),
    'token'             => env('WPP_TOKEN'),
    'webhook_verify'    => env('WPP_WEBHOOK_VERIFY'),
    // 'webhook_route'     => 'webhook/whatsapp',
    'database'          => [
        'skip_migrations'   => false,
        // 'table_name'        => null,
        // 'users_table'       => null,
        // 'users_table_pk'    => null,
        // 'user_phone_column' => 'phone',
    ],
];

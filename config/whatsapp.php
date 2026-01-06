<?php

return [
    'api_version' => env('WPP_API_VERSION', 'v20.0'),
    'business_id' => env('WPP_BUSINESS_ID'),
    'business_phone_id' => env('WPP_BUSINESS_PHONE_ID'),
    'server_url' => env('WPP_SERVER_URL', 'https://graph.facebook.com'),
    'token' => env('WPP_TOKEN'),
    'webhook_verify' => env('WPP_WEBHOOK_VERIFY'),
    'webhook_route' => env('WPP_WEBHOOK_ROUTE', 'webhook/whatsapp'),
    'database' => [
        'skip_migrations' => false,
        'user_model' => App\Models\User::class,
        'table_name' => 'whatsapp_messages',
        'users_table' => 'users',
        'users_table_pk' => 'id',
        'user_phone_column' => 'phone',
    ],
];

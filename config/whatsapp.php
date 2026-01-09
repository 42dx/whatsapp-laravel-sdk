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
        'messageable_id_column' => 'user_id',
        'skip_migrations' => false,
        'table_name' => 'whatsapp_messages',
        'user_model' => App\Models\User::class,
        'messageable_phone_column' => 'phone',
        'users_table_pk' => 'id',
        'users_table' => 'users',
    ],
];

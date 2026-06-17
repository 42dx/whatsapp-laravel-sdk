<?php

return [
    'api_version' => env('WHATSAPP_API_VERSION', 'v20.0'),
    'business_id' => env('WHATSAPP_BUSINESS_ID'),
    'business_phone_id' => env('WHATSAPP_BUSINESS_PHONE_ID'),
    'business_phone_number' => env('WHATSAPP_BUSINESS_PHONE_NUMBER'),
    'server_url' => env('WHATSAPP_SERVER_URL', 'https://graph.facebook.com'),
    'token' => env('WHATSAPP_TOKEN'),
    'webhook_verify' => env('WHATSAPP_WEBHOOK_VERIFY'),
    'webhook_route' => env('WHATSAPP_WEBHOOK_ROUTE', 'webhook/whatsapp'),
    'template_lang' => env('WHATSAPP_DEFAULT_TEMPLATE_LANGUAGE', 'en_US'),
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

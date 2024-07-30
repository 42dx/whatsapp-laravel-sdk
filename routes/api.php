<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Http\Controllers\WebhookController;

$webhookRoute = config('whatsapp.webhook_route', 'webhook/whatsapp');

Route::get($webhookRoute, [WebhookController::class, 'check']);
Route::post($webhookRoute, [WebhookController::class, 'handle']);

Route::get('/asd', function() {
    User::find(1)->sendWhatsappMsg(MessageType::TEXT, 'Test Yeah!');
});

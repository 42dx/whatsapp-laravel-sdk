<?php

use App\Models\User;
use Illuminate\Support\Facades\{Log, Route};
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Http\Controllers\WebhookController;

$webhookRoute = config('whatsapp.webhook_route');

Route::get($webhookRoute, [WebhookController::class, 'check']);
Route::post($webhookRoute, [WebhookController::class, 'handle']);

Route::get('/asd', function (): void {
    Log::info('Sending test whatsapp message');
    User::find(1)->sendWhatsappMsg(MessageType::TEXT, 'Test Yeah!');
});

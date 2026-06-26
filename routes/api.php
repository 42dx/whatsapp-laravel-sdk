<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Http\Controllers\WebhookController;

$webhookRoute = config('whatsapp.webhook_route');

Route::get($webhookRoute, [WebhookController::class, 'check']);
Route::post($webhookRoute, [WebhookController::class, 'handle']);

Route::get('asd', function(): void {
    /** @var User */
    $user = User::first();

    $user->sendWhatsappMsg(MessageType::TEXT, 'hey Gi!');
});

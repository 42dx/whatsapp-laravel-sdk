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

    /** @var User $user */
    $user = User::find(1);
    $user->sendWhatsappMsg(MessageType::REACTION, [
        'message_id' => 'wamid.HBgMNTU0MTk5MTU5NjY2FQIAEhgWM0VCMDE0RkI4OTM4QkM4MTVBNjJFNgA=',
        // 'emoji' => '👍',
    ]);
});

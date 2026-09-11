<?php

namespace The42dx\Whatsapp\Notifications\Channels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;
use The42dx\Whatsapp\Services\WhatsappService;

class WhatsappChannel {
    /**
     * send
     *
     * Sends a notification via the Whatsapp channel.
     *
     * @param  Model  $notifiable  The entity to be notified
     * @param  Notification  $notification  The notification instance
     */
    public function send(Model $notifiable, Notification $notification): void {
        if (!method_exists($notification, 'toWhatsapp')) {
            throw new InvalidArgumentException('Notification must implement toWhatsapp method.');
        }

        $message = $notification->toWhatsapp($notifiable);

        app(WhatsappService::class)->send($message, $notifiable);
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use The42dx\Whatsapp\Notifications\WhatsappNotification;

class Not extends WhatsappNotification {
    use Queueable;

    protected string $template = 'hello_world';

}

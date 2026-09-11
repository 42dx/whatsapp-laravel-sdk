<?php

namespace The42dx\Whatsapp\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;
use The42dx\Whatsapp\Factories\WhatsappApiMessage;
use The42dx\Whatsapp\Notifications\Channels\WhatsappChannel;

/**
 * WhatsappNotification
 *
 * Abstract class representing a notification that can be sent via WhatsApp.
 * It provides a structure for defining the notification's content and the channels through which it can be sent.
 * The standard whatsapp notification supports only templates, since only pre-approved templates can be sent to open the support window.
 * Other message types will only be received while support window is open, which is not the case for most notifications.
 * If you want to send other message types, use the The42dx\Whatsapp\Models\Traits\CanSendWhatsappMsg trait on your model and send messages directly.
 */
abstract class WhatsappNotification extends Notification {
    use Queueable;

    /**
     * The name of the WhatsApp template to be used for this notification.
     */
    protected string $template = '';

    /**
     * The language code for the WhatsApp template. This is used to specify the language in which the template should be sent.
     */
    protected ?string $language = null;

    /**
     * The components of the WhatsApp template. Components can include text, buttons, and other elements that make up the content of the template.
     */
    protected ?array $components = null;

    /**
     * Get the notification channels.
     */
    public function via(Model $notifiable): string {
        return WhatsappChannel::class;
    }

    /**
     * Get the voice representation of the notification.
     */
    public function toWhatsapp(Model $notifiable): WhatsappApiMessage {
        if (!$this->template || empty($this->template)) {
            throw new InvalidArgumentException('Template message data must include a name.');
        }

        return WhatsappApiMessage::compose($notifiable->{config('whatsapp.database.messageable_phone_column')})
            ->usingTemplate(name: $this->template, langCode: $this->language ?? null)
            ->handleComponents($this->components);
    }
}

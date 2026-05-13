<?php

namespace The42dx\Whatsapp\Models\Traits;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Models\WhatsappMessage;
use The42dx\Whatsapp\Services\WhatsappService;

/**
 * CanSendWhatsappMsg
 *
 * Trait to enable sending WhatsApp messages of various types.
 */
trait CanSendWhatsappMsg {
    /**
     * sendWhatsappMsg
     *
     * Send a WhatsApp message of the specified type with the given data.
     *
     * @param  MessageType  $type  The type of WhatsApp message to send.
     * @param  array|string  $data  The content or data of the message. Can be a string for simple text messages or an array for more complex message types.
     * @param  WhatsappMessage|null  $replyTo  The message to which this message is a reply.
     */
    public function sendWhatsappMsg(MessageType $type, array|string $data, ?WhatsappMessage $replyTo = null): void {
        switch ($type) {
            case MessageType::TEXT:
                $this->sendTextMessage($data, $replyTo);
                break;
            case MessageType::REACTION:
                $this->sendReactionMessage($data);
                break;
            case MessageType::AUDIO:
            case MessageType::BUTTON:
            case MessageType::CONTACTS:
            case MessageType::DOCUMENT:
            case MessageType::IMAGE:
            case MessageType::INTERACTIVE:
            case MessageType::LOCATION:
            case MessageType::STICKER:
            case MessageType::TEMPLATE:
            case MessageType::UNSUPPORTED:
            case MessageType::VIDEO:
            default:
                Log::warning('Unsupported message type: ' . $type->value);
        }
    }

    /**
     * sendTextMessage
     *
     * Helper method to send a text WhatsApp message.
     *
     * @param  string  $text  The text content of the message.
     * @param  WhatsappMessage  $replyTo  The message to which this message is a reply.
     */
    private function sendTextMessage(string $text, ?WhatsappMessage $replyTo): void {
        $whatsappService = app(WhatsappService::class);

        $whatsappService->send(
            MessageType::TEXT,
            $this,
            $text,
            $replyTo
        );
    }

    private function sendReactionMessage(array $data): void {
        $whatsappService = app(WhatsappService::class);

        $whatsappService->send(
            MessageType::REACTION,
            $this,
            $data
        );
    }
}

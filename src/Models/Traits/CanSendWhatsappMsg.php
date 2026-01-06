<?php

namespace The42dx\Whatsapp\Models\Traits;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Enums\MessageType;
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
     * @param MessageType $type The type of WhatsApp message to send.
     * @param array|string $data The data/content of the message.
     */
    public function sendWhatsappMsg(MessageType $type, array|string $data): void {
        switch ($type) {
            case MessageType::TEXT:
                $this->sendTextMessage($data);
                break;
            case MessageType::AUDIO:
            case MessageType::CONTACTS:
            case MessageType::DOCUMENT:
            case MessageType::IMAGE:
            case MessageType::INTERACTIVE:
            case MessageType::LOCATION:
            case MessageType::REACTION:
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
     * @param array|string $data The text content of the message.
     */
    private function sendTextMessage(array|string $data): void {
        $whatsappService = app(WhatsappService::class);

        $whatsappService->send(
            MessageType::TEXT,
            $this,
            $data
        );
    }
}

<?php

namespace The42dx\Whatsapp\Models\Traits;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Services\WhatsappService;

trait CanSendWhatsappMsg {
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

    private function sendTextMessage(array|string $data): void {
        $whatsappService = new WhatsappService;

        $whatsappService->send(
            MessageType::TEXT,
            $this->{config('whatsapp.database.user_phone_column', 'phone')},
            $data
        );
    }
}

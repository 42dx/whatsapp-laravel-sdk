<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\Message\StatusEntity;
use The42dx\Whatsapp\Enums\MessageStatus;
use The42dx\Whatsapp\Models\WhatsappMessage;

trait HandleMessageStatus {
    protected function handleStatus(StatusEntity $statusEntity): void {
        $message = WhatsappMessage::where('whatsapp_message_id', $statusEntity->id)
            ->first();

        if (!$message) {
            Log::debug('Message not found on the database');

            return;
        }

        $message->status = $statusEntity->status->value;

        switch ($statusEntity->status) {
            case MessageStatus::DELETED:
                $message->deleted_at = now();
                break;
            case MessageStatus::DELIVERED:
                $message->delivered_at = now();
                break;
            case MessageStatus::READ:
                $message->read_at = now();
                break;
            case MessageStatus::SENT:
                $message->sent_at = now();
                break;
        }

        $message->save();

        Log::debug('Message status handled ');
    }
}

<?php

namespace The42dx\Whatsapp\Traits;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\Changes\{ContactsEntity, MessagesEntity};
use The42dx\Whatsapp\Entities\Message\{MessageEntity, StatusEntity};
use The42dx\Whatsapp\Enums\MessageType;

trait HandleMessages {
    protected function handleMessages(MessagesEntity $messagesValue): void {
        $this->handleMetaData($messagesValue);

        if (isset($messagesValue->messages) && !is_null($messagesValue->messages)) {
            $messagesValue->messages->each(function ($message) {
                $this->handleMessage($message);
            });
        }

        if (isset($messagesValue->statuses) && !is_null($messagesValue->statuses)) {
            $messagesValue->statuses->each(function ($status) {
                $this->handleStatus($status);
            });
        }
    }

    protected function handleMetaData(MessagesEntity $messages): void {
        $messages->waId; // Todo Handle
        $messages->phone; // Todo Handle

        if (isset($messages->contacts) && !is_null($messages->contacts)) {
            $messages->contacts->each(function ($contact) {
                $this->handleContact($contact);
            });
        }
    }

    protected function handleContact(ContactsEntity $contact): void {
        $contact; // Todo Handle
    }

    protected function handleMessage(MessageEntity $message): void {
        $message->from;
        $message->id;
        $message->timestamp;
        $message->type;

        switch ($message->type) {
            case MessageType::TEXT:
                $this->handleText($message);
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
                Log::warning('Unsupported message type: ' . $message->type->value);
                break;
        }
    }

    protected function handleStatus(StatusEntity $status): void {
        $status; // Todo Handle
    }

    protected function handleText(MessageEntity $message): void {
        $message; // Todo Handle
    }
}

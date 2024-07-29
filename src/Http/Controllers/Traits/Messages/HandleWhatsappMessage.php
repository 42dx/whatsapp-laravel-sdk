<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\{Changes\MessagesEntity, Message\MessageEntity};
use The42dx\Whatsapp\Enums\{MessageType, MessageWay};
use The42dx\Whatsapp\Models\WhatsappMessage;
use The42dx\Whatsapp\Http\Controllers\Traits\Messages\{HandleMessageStatus, HandleTextMessage};

trait HandleWhatsappMessage {
    use HandleTextMessage, HandleMessageStatus;

    private WhatsappMessage $message;

    protected function handleMessages(MessagesEntity $messagesValue): void {
        $this->message = new WhatsappMessage();

        $this->handleMetaData($messagesValue);

        if (!is_null($messagesValue->messages)) {

            $messagesValue->messages->each(function ($message) {
                $this->handleMessage($message);
            });
        }

        if (!is_null($messagesValue->statuses)) {
            $messagesValue->statuses->each(function ($status) {
                $this->handleStatus($status);
            });
        }
    }

    protected function handleMetaData(MessagesEntity $messages): void {
        $this->message->api_phone_number =  $messages->phone;
    }

    protected function handleMessage(MessageEntity $message): void {
        $this->message->contact_phone_number = $message->from;
        $this->message->type                 = $message->type;
        $this->message->whatsapp_message_id  = $message->id;
        $this->message->way                  = MessageWay::INBOUND;

        $user = User::where(
            config('whatsapp.database.user_phone_column', 'phone'),
            $message->from
        )->first();

        if(!!$user) {
            $this->message->user_id = $user->id;
        }

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

        $this->message->save();
    }
}

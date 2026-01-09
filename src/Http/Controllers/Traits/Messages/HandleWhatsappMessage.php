<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\{Changes\MessagesEntity, Message\MessageEntity};
use The42dx\Whatsapp\Enums\{MessageType, MessageWay};
use The42dx\Whatsapp\Models\WhatsappMessage;

/**
 * HandleWhatsappMessage
 *
 * Trait to handle general Whatsapp messages
 *
 * @see \The42dx\Whatsapp\Entities\Changes\MessagesEntity
 * @see \The42dx\Whatsapp\Entities\Message\MessageEntity
 * @see \The42dx\Whatsapp\Models\WhatsappMessage
 * @see \The42dx\Whatsapp\Enums\MessageType
 * @see \The42dx\Whatsapp\Http\Controllers\Traits\Messages\HandleTextMessage
 * @see \The42dx\Whatsapp\Http\Controllers\Traits\Messages\HandleMessageStatus
 */
trait HandleWhatsappMessage {
    use HandleMessageStatus, HandleTextMessage;

    /**
     * handleMessages
     *
     * Handle the messages received from Whatsapp, and creates or updates them in the database.
     * Handles both general messages and their statuses (read|pending|sent|etc).
     *
     * @param  \The42dx\Whatsapp\Entities\Changes\MessagesEntity  $messagesValue  The whatsapp API messages entity
     */
    protected function handleMessages(MessagesEntity $messagesValue): void {
        if (!is_null($messagesValue->messages)) {
            $messagesValue->messages->each(function ($message): void {
                $this->handleMessage($message);
            });
        }

        if (!is_null($messagesValue->statuses)) {
            $messagesValue->statuses->each(function ($status): void {
                $this->handleStatus($status);
            });
        }
    }

    /**
     * handleMessage
     *
     * Handle a single message received from Whatsapp
     *
     * This method instantiates a WhatsappMessage model, handles its general properties,
     * and processes the message based on its type.
     * After processing, it updates or creates the message record in the database.
     *
     * @param  \The42dx\Whatsapp\Entities\Message\MessageEntity  $message  The whatsapp API message entity
     */
    protected function handleMessage(MessageEntity $message): void {
        $messageModel = new WhatsappMessage;
        $messageModel->contact_phone_number = $message->from;
        $messageModel->type = $message->type;
        $messageModel->whatsapp_message_id = $message->id;
        $messageModel->way = MessageWay::INBOUND; // might need to change this logic when sending messages through the SDK

        $user = app(config('whatsapp.database.user_model'))
            ->where(config('whatsapp.database.messageable_phone_column'), $message->from)
            ->first();

        if (isset($user) && !is_null($user) && !empty($user)) {
            $messageModel->user_id = $user->id;
        }

        switch ($message->type) {
            case MessageType::TEXT:
                $messageModel = $this->handleText($messageModel, $message);
                break;
            case MessageType::AUDIO:
            case MessageType::BUTTON:
            case MessageType::CONTACTS:
            case MessageType::DOCUMENT:
            case MessageType::IMAGE:
            case MessageType::INTERACTIVE:
            case MessageType::LOCATION:
            case MessageType::REACTION:
            case MessageType::STICKER:
            case MessageType::VIDEO:
            default:
                Log::warning('Unsupported message type: ' . $message->type->value);
                break;
        }

        WhatsappMessage::updateOrCreate(
            [
                'whatsapp_message_id' => $messageModel->whatsapp_message_id,
                'contact_phone_number' => $messageModel->contact_phone_number,
            ],
            Arr::except($messageModel->toArray(), ['whatsapp_message_id', 'contact_phone_number'])
        );
    }
}

<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\{Changes\MessagesEntity, Message\MessageEntity};
use The42dx\Whatsapp\Entities\Message\ContactEntity;
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
 * @see \The42dx\Whatsapp\Http\Controllers\Traits\Messages\HandleMessageMetadata
 */
trait HandleWhatsappMessage {
    use HandleMessageMetadata, HandleTemplateMessage, HandleTextMessage;

    public const MSG_NOT_FOUND_ERROR_MSG = 'Message not found on the database: ';

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
            $contact = $messagesValue->contact[0];
            $messagesValue->messages->each(function($message) use ($contact): void {
                $this->handleMessage($message, $contact);
            });
        }

        if (!is_null($messagesValue->statuses)) {
            $messagesValue->statuses->each(function($newStatus): void {
                $message = WhatsappMessage::where('whatsapp_message_id', $newStatus->id)->first();

                if (!$message) {
                    Log::warning(self::MSG_NOT_FOUND_ERROR_MSG . $newStatus->id);

                    return;
                }

                $newStatusMsg = $this->handleStatus($message, $newStatus);

                if ($newStatusMsg) {
                    $newStatusMsg->save();

                    Log::info('Message status update handled: ' . $newStatus->id);
                }
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
     * @param  \The42dx\Whatsapp\Entities\Message\ContactEntity  $contact  The Whatsapp API contact entity
     */
    protected function handleMessage(MessageEntity $message, ?ContactEntity $contact): void {
        $messageModel = new WhatsappMessage;
        $messageModel->contact_phone_number = $message->from;
        $messageModel->type = $message->type;
        $messageModel->whatsapp_message_id = $message->id;
        $messageModel->way = MessageWay::INBOUND; // might need to change this logic when sending messages through the SDK

        $user = app(config('whatsapp.database.user_model'))
            ->where(config('whatsapp.database.messageable_phone_column'), $message->from)
            ->first();

        if (isset($user) && !is_null($user) && !empty($user)) {
            $messageModel->{config('whatsapp.database.messageable_id_column')} = $user->id;
        }

        switch ($message->type) {
            case MessageType::TEXT:
                $messageModel = $this->handleText($messageModel, $message);
                break;
            case MessageType::REACTION:
                $existingMsg = WhatsappMessage::where('whatsapp_message_id', $message->reaction->messageId)->first();

                if (!$existingMsg) {
                    Log::warning(self::MSG_NOT_FOUND_ERROR_MSG . $message->reaction->messageId);

                    return;
                }

                $messageModel = $this->handleReaction($existingMsg, $message);

                break;
            case MessageType::BUTTON:
                $messageModel = $this->handleButton($messageModel, $message);
                break;
            case MessageType::AUDIO:
            case MessageType::CONTACTS:
            case MessageType::DOCUMENT:
            case MessageType::IMAGE:
            case MessageType::INTERACTIVE:
            case MessageType::LOCATION:
            case MessageType::STICKER:
            case MessageType::VIDEO:
            default:
                Log::warning('Unsupported message type: ' . $message->type->value);
                break;
        }

        $messageModel = $this->handleContext($messageModel, $message->context);

        WhatsappMessage::updateOrCreate(
            [
                'whatsapp_message_id' => $messageModel->whatsapp_message_id,
                'contact_phone_number' => $messageModel->contact_phone_number,
                'profile_name' => $contact->name ?? null,
            ],
            Arr::except($messageModel->toArray(), ['whatsapp_message_id', 'contact_phone_number'])
        );
    }
}

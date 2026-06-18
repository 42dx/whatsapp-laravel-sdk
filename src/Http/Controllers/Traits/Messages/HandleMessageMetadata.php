<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\Message\{ContextEntity, MessageEntity, StatusEntity};
use The42dx\Whatsapp\Enums\{ContextType, MessageStatus, MessageType};
use The42dx\Whatsapp\Models\WhatsappMessage;

/**
 * HandleMessageMetadata
 *
 * Trait to handle Whatsapp message metadata like status updates (read, sent, delivered, deleted),
 * timestamps updates, contexts (reply, forward) etc.
 *
 * @see \The42dx\Whatsapp\Entities\Message\StatusEntity
 * @see \The42dx\Whatsapp\Enums\MessageStatus
 * @see \The42dx\Whatsapp\Models\WhatsappMessage
 * @see \The42dx\Whatsapp\Http\Controllers\Traits\Messages\HandleWhatsappMessage
 */
trait HandleMessageMetadata {
    /**
     * handleStatus
     *
     * Handles the update of message status updates received from Whatsapp.
     *
     * @param  \The42dx\Whatsapp\Entities\Message\StatusEntity  $statusEntity  The status entity containing the status update data
     */
    protected function handleStatus(WhatsappMessage $messageModel, StatusEntity $statusEntity): ?WhatsappMessage {
        $now = now();
        $messageModel->status = $statusEntity->status->value;

        switch ($statusEntity->status) {
            case MessageStatus::DELETED:
                $messageModel->whatsapp_deleted_at = $now;
                break;
            case MessageStatus::DELIVERED:
                $messageModel->delivered_at = $now;
                break;
            case MessageStatus::READ:
                $messageModel->read_at = $now;
                break;
            case MessageStatus::FAILED:
                $messageModel->failed_at = $now;
                break;
            case MessageStatus::SENT:
                $messageModel->sent_at = $now;
                break;
            default:
                Log::warning('Unhandled message status: ' . $statusEntity->status->value);

                return null;
        }

        return $messageModel;
    }

    /**
     * handleContext
     *
     * Handles the update of message context received from Whatsapp.
     *
     * @param  \The42dx\Whatsapp\Models\WhatsappMessage  $messageModel  The message model to update
     * @param  \The42dx\Whatsapp\Entities\Message\ContextEntity  $ctx  The context entity containing the context data
     * @return \The42dx\Whatsapp\Models\WhatsappMessage The updated message model
     */
    protected function handleContext(WhatsappMessage $messageModel, ContextEntity $ctx): WhatsappMessage {
        if (count($messageModel->getPayloadType([ContextType::REPLY, ContextType::FWD, ContextType::F_FWD]))) {
            Log::info('Message already has context, skipping context handling');

            return $messageModel;
        }

        if ($ctx->type) {
            $messageModel->payload = array_merge(
                $messageModel->payload ?? [],
                [
                    ['type' => $ctx->type, 'context' => $ctx->id, 'sender' => $ctx->from],
                ]
            );
        }

        Log::info('Message context handled');

        return $messageModel;
    }

    /**
     * handleReaction
     *
     * Handles the processing of reaction messages received from Whatsapp.
     * It populates the WhatsappMessage model with reaction data, or removes the reaction if the emoji is not set (indicating a reaction removal).
     *
     * @param  \The42dx\Whatsapp\Models\WhatsappMessage  $messageModel  The WhatsappMessage model instance to populate
     * @param  \The42dx\Whatsapp\Entities\Message\MessageEntity  $message  The message entity containing the reaction data
     * @return \The42dx\Whatsapp\Models\WhatsappMessage The populated WhatsappMessage model
     */
    protected function handleReaction(WhatsappMessage $messageModel, MessageEntity $message): WhatsappMessage {
        $reactionsModelPayload = $messageModel->getPayloadType(MessageType::REACTION);
        $noReactionsModelPayload = $messageModel->getPayloadWithoutType(MessageType::REACTION);

        if ($message->reaction->emoji) {
            $reactionsModelPayload[] = [
                'type' => MessageType::REACTION->value,
                'emoji' => $message->reaction->emoji,
                'from' => $message->from,
            ];
        } else {
            $reactionsModelPayload = array_filter($reactionsModelPayload, fn($reaction) => $reaction['from'] !== $message->from);
        }

        $messageModel->payload = array_merge($noReactionsModelPayload, $reactionsModelPayload);

        Log::info('Reaction message handled');

        return $messageModel;
    }
}

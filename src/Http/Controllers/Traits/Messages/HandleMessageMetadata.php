<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\Message\{ContextEntity, StatusEntity};
use The42dx\Whatsapp\Enums\MessageStatus;
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
    protected function handleStatus(StatusEntity $statusEntity): void {
        $message = WhatsappMessage::where('whatsapp_message_id', $statusEntity->id)
            ->first();

        if (!$message) {
            Log::debug('Message not found on the database: ' . $statusEntity->id);

            return;
        }

        $now = now();
        $message->status = $statusEntity->status->value;

        switch ($statusEntity->status) {
            case MessageStatus::DELETED:
                $message->deleted_at = $now;
                break;
            case MessageStatus::DELIVERED:
                $message->delivered_at = $now;
                break;
            case MessageStatus::READ:
                $message->read_at = $now;
                break;
            case MessageStatus::SENT:
                $message->sent_at = $now;
                break;
            default:
                Log::warning('Unhandled message status: ' . $statusEntity->status->value);
        }

        $message->save();

        Log::info('Message status update handled ');
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
        $messageModel->ctx = $ctx->id ?? null;
        $messageModel->ctx_type = $ctx->type;

        Log::info('Message context handled');

        return $messageModel;
    }
}

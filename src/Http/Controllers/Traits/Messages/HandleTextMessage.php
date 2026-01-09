<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\Message\MessageEntity;
use The42dx\Whatsapp\Models\WhatsappMessage;

/**
 * HandleTextMessage
 *
 * Trait providing functionality to handle text messages received via Whatsapp.
 *
 * @see \The42dx\Whatsapp\Entities\Message\MessageEntity
 * @see \The42dx\Whatsapp\Models\WhatsappMessage
 * @see \The42dx\Whatsapp\Http\Controllers\Traits\Messages\HandleWhatsappMessage
 */
trait HandleTextMessage {
    /**
     * handleText
     *
     * Handles the processing of text messages received from Whatsapp.
     * It populates the WhatsappMessage model with text content.
     *
     * @param  \The42dx\Whatsapp\Models\WhatsappMessage  $messageModel  The WhatsappMessage model instance to populate
     * @param  \The42dx\Whatsapp\Entities\Message\MessageEntity  $message  The message entity containing the text data
     * @return \The42dx\Whatsapp\Models\WhatsappMessage The populated WhatsappMessage model
     */
    protected function handleText(WhatsappMessage $messageModel, MessageEntity $message): WhatsappMessage {
        $messageModel->text = $message->text;

        Log::debug('Text message handled');

        return $messageModel;
    }
}

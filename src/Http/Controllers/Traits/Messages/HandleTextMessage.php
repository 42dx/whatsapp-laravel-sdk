<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\Message\MessageEntity;
use The42dx\Whatsapp\Models\WhatsappMessage;

trait HandleTextMessage {
    protected function handleText(WhatsappMessage $messageModel, MessageEntity $message): WhatsappMessage {
        $messageModel->text = $message->text;

        Log::debug('Text message handled');

        return $messageModel;
    }
}

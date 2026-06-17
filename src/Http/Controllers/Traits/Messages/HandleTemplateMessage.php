<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\Message\MessageEntity;
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Models\WhatsappMessage;

trait HandleTemplateMessage {
    protected function handleButton(WhatsappMessage $messageModel, MessageEntity $message): WhatsappMessage {
        $messageModel->text = $message->button->text;
        $messageModel->payload = array_merge(
            $messageModel->payload ?? [],
            [[
                'type' => MessageType::BUTTON->value,
                'data' => $message->button->payload,
            ]]
        );

        Log::info('Button message handled');

        return $messageModel;
    }
}

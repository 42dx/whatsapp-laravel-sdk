<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\Message\MessageEntity;

trait HandleTextMessage {
    protected function handleText(MessageEntity $message): void {
        $this->message->text = $message->text;

        Log::debug('Text message handled');
    }
}

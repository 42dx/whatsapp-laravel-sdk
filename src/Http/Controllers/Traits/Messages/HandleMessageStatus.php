<?php

namespace The42dx\Whatsapp\Http\Controllers\Traits\Messages;

use Illuminate\Support\Facades\Log;
use The42dx\Whatsapp\Entities\Message\MessageEntity;
use The42dx\Whatsapp\Entities\Message\StatusEntity;

trait HandleMessageStatus {
    protected function handleStatus(StatusEntity $status): void {
        $status; // Todo Handle
    }
}

<?php

namespace The42dx\Whatsapp\Tests\Unit\Http\Controllers\Traits\Messages;

use The42dx\Whatsapp\Entities\Message\MessageEntity;
use The42dx\Whatsapp\Http\Controllers\Traits\Messages\HandleTextMessage;
use The42dx\Whatsapp\Models\WhatsappMessage;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class HandleTextMessageTest extends UnitTestCase {
    use HandleTextMessage;

    public function test_handle_text_it_should_store_text_in_message_model_and_return_it(): void {
        $messageModel = new WhatsappMessage;
        $messageEntity = new MessageEntity(['text' => ['body' => 'Hello, World!']]);

        $result = $this->handleText($messageModel, $messageEntity);

        $this->assertInstanceOf(WhatsappMessage::class, $result);
        $this->assertEquals('Hello, World!', $result->text);
    }
}

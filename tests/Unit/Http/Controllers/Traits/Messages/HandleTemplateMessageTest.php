<?php

namespace The42dx\Whatsapp\Tests\Unit\Http\Controllers\Traits\Messages;

use The42dx\Whatsapp\Entities\Message\MessageEntity;
use The42dx\Whatsapp\Enums\{MessageStatus, MessageType};
use The42dx\Whatsapp\Http\Controllers\Traits\Messages\HandleTemplateMessage;
use The42dx\Whatsapp\Models\WhatsappMessage;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class HandleTemplateMessageTest extends UnitTestCase {
    use HandleTemplateMessage;

    public function test_handle_button_it_should_update_message_payload_with_button_data(): void {
        $data = 'some_btn_data';
        $text = 'some btn text';
        $messageModel = WhatsappMessage::factory()->withStatus(MessageStatus::READ)->make();
        $messageEntity = new MessageEntity(['id' => 'wamid.2343242423', 'from' => '22222222222', 'button' => ['text' => $text, 'payload' => $data]]);

        $this->handleButton($messageModel, $messageEntity);

        $this->assertCount(1, $messageModel->payload);
        $this->assertEquals(MessageType::BUTTON->value, $messageModel->payload[0]['type']);
        $this->assertEquals($data, $messageModel->payload[0]['data']);
        $this->assertEquals($text, $messageModel->text);
    }
}

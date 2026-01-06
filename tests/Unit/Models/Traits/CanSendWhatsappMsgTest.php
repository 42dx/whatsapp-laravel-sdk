<?php

namespace The42dx\Whatsapp\Tests\Unit\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Models\Traits\CanSendWhatsappMsg;
use The42dx\Whatsapp\Services\WhatsappService;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class UserModel extends Model {
    use CanSendWhatsappMsg;
}

class CanSendWhatsappMsgTest extends UnitTestCase {
    private UserModel $user;

    private MockInterface $whatsappServiceMock;

    protected function setUp(): void {
        parent::setUp();

        $this->whatsappServiceMock = $this->mock(WhatsappService::class);
        Log::spy();

        $this->user = new UserModel;
    }

    public static function msgTypeDataset(): array {
        return [
            MessageType::TEXT->value => [MessageType::TEXT, 'Test text message'],
            MessageType::AUDIO->value => [MessageType::AUDIO, ''],
            MessageType::CONTACTS->value => [MessageType::CONTACTS, ''],
            MessageType::DOCUMENT->value => [MessageType::DOCUMENT, ''],
            MessageType::IMAGE->value => [MessageType::IMAGE, ''],
            MessageType::INTERACTIVE->value => [MessageType::INTERACTIVE, ''],
            MessageType::LOCATION->value => [MessageType::LOCATION, ''],
            MessageType::REACTION->value => [MessageType::REACTION, ''],
            MessageType::STICKER->value => [MessageType::STICKER, ''],
            MessageType::TEMPLATE->value => [MessageType::TEMPLATE, ''],
            MessageType::UNSUPPORTED->value => [MessageType::UNSUPPORTED, ''],
            MessageType::VIDEO->value => [MessageType::VIDEO, ''],
        ];
    }

    #[DataProvider('msgTypeDataset')]
    public function test__send_whatsapp_msg__it_should_call_the_correct_send_message_method_based_on_message_type(MessageType $messageType, string $message): void {
        // remove the condition when all other message types are implemented
        if ($messageType === MessageType::TEXT) {
            $this->whatsappServiceMock
                ->shouldReceive('send')
                ->with($messageType, $this->user, $message)
                ->once();

            $this->user->sendWhatsappMsg($messageType, $message);

            $this->addToAssertionCount(1);
        } else {
            $this->markTestIncomplete('This test has not been implemented yet.');
        }
    }
}

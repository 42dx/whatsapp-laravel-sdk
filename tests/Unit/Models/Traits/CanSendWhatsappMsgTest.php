<?php

namespace The42dx\Whatsapp\Tests\Unit\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use The42dx\Whatsapp\Enums\{MessageComponent, MessageType};
use The42dx\Whatsapp\Factories\WhatsappApiMessage;
use The42dx\Whatsapp\Models\Traits\CanSendWhatsappMsg;
use The42dx\Whatsapp\Models\WhatsappMessage;
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
        $this->user->phone = '13213213212';
    }

    public static function msgTypeDataset(): array {
        return [
            MessageType::TEXT->value => [MessageType::TEXT, 'Test text message'],
            MessageType::REACTION->value => [MessageType::REACTION, '👍'],
            MessageType::TEMPLATE->value => [MessageType::TEMPLATE, ['name' => 'test_template', 'components' => [['type' => MessageComponent::BODY, 'parameters' => [['text' => 'some text']]]]]],

            MessageType::AUDIO->value => [MessageType::AUDIO, ''],
            MessageType::BUTTON->value => [MessageType::BUTTON, ''],
            MessageType::CONTACTS->value => [MessageType::CONTACTS, ''],
            MessageType::DOCUMENT->value => [MessageType::DOCUMENT, ''],
            MessageType::IMAGE->value => [MessageType::IMAGE, ''],
            MessageType::INTERACTIVE->value => [MessageType::INTERACTIVE, ''],
            MessageType::LOCATION->value => [MessageType::LOCATION, ''],
            MessageType::STICKER->value => [MessageType::STICKER, ''],
            MessageType::UNSUPPORTED->value => [MessageType::UNSUPPORTED, ''],
            MessageType::VIDEO->value => [MessageType::VIDEO, ''],
        ];
    }

    #[DataProvider('msgTypeDataset')]
    public function test__send_whatsapp_msg__it_should_call_the_correct_send_message_method_based_on_message_type(MessageType $messageType, array|string $data): void {
        $replyTo = null;
        // remove the condition when all other message types are implemented
        if ($messageType === MessageType::TEXT) {
            $this->whatsappServiceMock
                ->shouldReceive('send')
                ->with(Mockery::type(WhatsappApiMessage::class), $this->user)
                ->once();
        } elseif ($messageType === MessageType::REACTION) {
            $replyTo = new WhatsappMessage;
            $replyTo->whatsapp_message_id = '123456';

            $this->whatsappServiceMock
                ->shouldReceive('send')
                ->with(Mockery::type(WhatsappApiMessage::class), $this->user)
                ->once();
        } elseif ($messageType === MessageType::TEMPLATE) {
            $this->whatsappServiceMock
                ->shouldReceive('send')
                ->with(Mockery::type(WhatsappApiMessage::class), $this->user)
                ->once();
        } else {
            Log::shouldReceive('warning')
                ->with('Unsupported message type: ' . $messageType->value)
                ->once();
        }

        $this->user->sendWhatsappMsg($messageType, $data, $replyTo);

        $this->addToAssertionCount(1);
    }

    public function test__send_whatsapp_msg__it_should_use_the_provided_template_language(): void {
        $data = [
            'name' => 'test_template',
            'lang' => 'en_US',
            'components' => [
                [
                    'type' => MessageComponent::BODY,
                    'parameters' => [['text' => 'some text']],
                ],
            ],
        ];

        $this->whatsappServiceMock
            ->shouldReceive('send')
            ->with(
                Mockery::on(fn(WhatsappApiMessage $message): bool => $message->template['name'] === 'test_template' && $message->template['language']['code'] === 'en_US'),
                $this->user
            )
            ->once();

        $this->user->sendWhatsappMsg(MessageType::TEMPLATE, $data);

        $this->addToAssertionCount(1);
    }

    public function test__send_whatsapp_msg__it_should_require_template_name(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Template message data must include a name.');

        $this->user->sendWhatsappMsg(MessageType::TEMPLATE, ['template' => 'test_template']);
    }
}

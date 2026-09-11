<?php

namespace The42dx\Whatsapp\Tests\Unit\Notifications\Channels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\{Notifiable, Notification};
use InvalidArgumentException;
use Mockery;
use Mockery\MockInterface;
use The42dx\Whatsapp\Factories\WhatsappApiMessage;
use The42dx\Whatsapp\Notifications\Channels\WhatsappChannel;
use The42dx\Whatsapp\Notifications\WhatsappNotification;
use The42dx\Whatsapp\Services\WhatsappService;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class WhatsappChannelNotifiable extends Model {
    use Notifiable;
}

class WhatsappChannelTest extends UnitTestCase {
    private WhatsappChannelNotifiable $notifiable;

    private MockInterface $whatsappServiceMock;

    protected function setUp(): void {
        parent::setUp();

        $this->notifiable = new WhatsappChannelNotifiable;
        $this->whatsappServiceMock = $this->mock(WhatsappService::class);
    }

    public function test__send__it_should_send_the_notification_message_to_the_notifiable(): void {
        $message = WhatsappApiMessage::compose('13213213212');
        $notification = Mockery::mock(WhatsappNotification::class);

        $notification
            ->shouldReceive('toWhatsapp')
            ->with($this->notifiable)
            ->once()
            ->andReturn($message);

        $this->whatsappServiceMock
            ->shouldReceive('send')
            ->with($message, $this->notifiable)
            ->once();

        (new WhatsappChannel)->send($this->notifiable, $notification);

        $this->addToAssertionCount(1);
    }

    public function test__send__it_should_reject_notifications_without_a_to_whatsapp_method(): void {
        $notification = new class extends Notification {};

        $this->whatsappServiceMock->shouldNotReceive('send');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Notification must implement toWhatsapp method.');

        (new WhatsappChannel)->send($this->notifiable, $notification);
    }
}

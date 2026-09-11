<?php

namespace The42dx\Whatsapp\Tests\Unit\Notifications;

use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use The42dx\Whatsapp\Enums\{MessageComponent, MessageType};
use The42dx\Whatsapp\Factories\WhatsappApiMessage;
use The42dx\Whatsapp\Notifications\Channels\WhatsappChannel;
use The42dx\Whatsapp\Notifications\WhatsappNotification;
use The42dx\Whatsapp\Tests\Fixtures\Models\User;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class WhatsappNotificationTest extends UnitTestCase {
    public function test__via__it_should_use_the_whatsapp_channel(): void {
        $notification = new class extends WhatsappNotification {};

        $this->assertSame(WhatsappChannel::class, $notification->via(new User));
    }

    public function test__to_whatsapp__it_should_build_a_template_message_from_the_notification_data(): void {
        Config::set('whatsapp.database.messageable_phone_column', 'mobile_number');

        $notifiable = new User;
        $notifiable->mobile_number = '13213213212';

        $notification = new class extends WhatsappNotification {
            public function __construct() {
                $this->template = 'appointment_reminder';
                $this->language = 'pt_BR';
                $this->components = [
                    [
                        'type' => MessageComponent::BODY,
                        'parameters' => [
                            [
                                'name' => 'customer_name',
                                'text' => 'Rafael',
                            ],
                        ],
                    ],
                ];
            }
        };

        $message = $notification->toWhatsapp($notifiable);

        $this->assertInstanceOf(WhatsappApiMessage::class, $message);
        $this->assertSame('13213213212', $message->to);
        $this->assertSame(MessageType::TEMPLATE, $message->type);
        $this->assertSame([
            'language' => ['code' => 'pt_BR'],
            'name' => 'appointment_reminder',
            'components' => [
                [
                    'type' => MessageComponent::BODY->value,
                    'sub_type' => null,
                    'index' => null,
                    'parameters' => [
                        [
                            'type' => MessageComponent::TEXT->value,
                            'parameter_name' => 'customer_name',
                            'coupon_code' => null,
                            'text' => 'Rafael',
                        ],
                    ],
                ],
            ],
        ], $message->template);
    }

    public function test__to_whatsapp__it_should_use_the_configured_language_when_optional_data_is_missing(): void {
        Config::set('whatsapp.template_lang', 'es_ES');

        $notification = new class extends WhatsappNotification {
            public function __construct() {
                $this->template = 'appointment_reminder';
            }
        };

        $message = $notification->toWhatsapp(new User(['phone' => '13213213212']));

        $this->assertSame('es_ES', $message->template['language']['code']);
        $this->assertArrayNotHasKey('components', $message->template);
    }

    public function test__to_whatsapp__it_should_require_a_template_name(): void {
        $notification = new class extends WhatsappNotification {};

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Template message data must include a name.');

        $notification->toWhatsapp(new User);
    }
}

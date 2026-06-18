<?php

use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Response as Res;
use Illuminate\Support\Facades\{Config, Log, Schema};
use The42dx\Whatsapp\Enums\{ContextType, MessageComponent, MessageType, MessageWay};
use The42dx\Whatsapp\Factories\WhatsappApiMessage;
use The42dx\Whatsapp\Models\WhatsappMessage;
use The42dx\Whatsapp\Services\WhatsappService;
use The42dx\Whatsapp\Tests\Fixtures\Models\User;
use The42dx\Whatsapp\Tests\Integration\IntegrationTestCase;

class WhatsappServiceTest extends IntegrationTestCase {
    protected function setUp(): void {
        parent::setUp();

        User::create(['phone' => '111111111111']);
    }

    public function test__construct__it_initializes_service(): void {
        $wppService = new WhatsappService($this->http);

        $this->assertNotNull($wppService);
        $this->assertInstanceOf(WhatsappService::class, $wppService);
    }

    public function test__construct__if_throws_exception_on_invalid_config(): void {
        $this->expectException(InvalidArgumentException::class);

        Config::set('whatsapp.business_phone_id', null);

        new WhatsappService;
    }

    public function test__send__it_log_whatsapp_api_errors(): void {
        Log::spy();
        Log::shouldReceive('error')
            ->once()
            ->with('Error sending whatsapp message', Mockery::type('array'));

        $this->mock->append(new Response(Res::HTTP_UNPROCESSABLE_ENTITY, [], json_encode(['error' => 'some error message'])));

        $result = $this->whatsappService->send(new WhatsappApiMessage('1111111'));

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test__send__it_log_unhandled_exceptions(): void {
        Log::spy();
        Log::shouldReceive('error')
            ->once()
            ->with('Error sending whatsapp message', Mockery::type('array'));

        $this->mock->append(new Response(500, [], json_encode(['error' => 'some error message'])));

        $user = User::first();
        $result = $this->whatsappService->send(new WhatsappApiMessage('1111111'), $user);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test__send__it_creates_message_record_on_successful_send(): void {
        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-whatsapp-msd-id']]])));

        $user = User::first();
        $apiMsg = WhatsappApiMessage::compose(to: $user->phone)->with(text: 'Some text message');
        $result = $this->whatsappService->send($apiMsg, $user);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('messages', $result);
        $this->assertNotEmpty($result['messages']);
        $this->assertDatabaseHas('whatsapp_messages', [
            'user_id' => $user->id,
            'type' => MessageType::TEXT,
            'whatsapp_message_id' => 'some-whatsapp-msd-id',
            'text' => 'Some text message',
            'way' => MessageWay::OUTBOUND->value,
        ]);
    }

    public function test__send__it_should_use_configured_messageable_id_column_on_message_record(): void {
        Config::set('whatsapp.database.messageable_id_column', 'customer_id');

        Schema::table('whatsapp_messages', function(Blueprint $table): void {
            $table->unsignedBigInteger('customer_id')->nullable();
        });

        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-whatsapp-msd-id']]])));

        $user = User::first();
        $apiMsg = WhatsappApiMessage::compose(to: $user->phone)->with(text: 'Some text message');
        $result = $this->whatsappService->send($apiMsg, $user);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('messages', $result);
        $this->assertDatabaseHas('whatsapp_messages', [
            'customer_id' => $user->id,
            'whatsapp_message_id' => 'some-whatsapp-msd-id',
        ]);
    }

    public function test__send__it_should_create_message_record_with_reply_context_on_successful_send(): void {
        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-whatsapp-msg-id']]])));

        $user = User::first();
        $apiMsg = WhatsappApiMessage::compose(to: $user->phone)->with(text: 'Some text message');
        $result = $this->whatsappService->send($apiMsg, $user);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('messages', $result);
        $this->assertNotEmpty($result['messages']);

        $toBeRepliedTo = WhatsappMessage::first();

        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-reply-msg-id', 'context' => ['id' => $toBeRepliedTo->whatsapp_message_id, 'from' => '2222222222222']]]])));

        $apiMsg = WhatsappApiMessage::compose(to: $user->phone)->replyTo($toBeRepliedTo->whatsapp_message_id)->with(text: 'Some reply message');

        $result = $this->whatsappService->send($apiMsg, $user);

        $this->assertDatabaseHas('whatsapp_messages', [
            'user_id' => $user->id,
            'type' => MessageType::TEXT,
            'whatsapp_message_id' => 'some-reply-msg-id',
            'text' => 'Some reply message',
            'way' => MessageWay::OUTBOUND->value,
            'payload' => json_encode([['type' => ContextType::REPLY->value, 'context' => $toBeRepliedTo->whatsapp_message_id]]),
        ]);
    }

    public function test__send__it_should_update_message_record_with_reaction_on_successful_send(): void {
        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-whatsapp-msg-id']]])));

        $user = User::first();
        $apiMsg = WhatsappApiMessage::compose(to: $user->phone)->with(text: 'Some text message');
        $result = $this->whatsappService->send($apiMsg, $user);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('messages', $result);
        $this->assertNotEmpty($result['messages']);

        $toBeReactedTo = WhatsappMessage::first();

        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-reply-msg-id']]])));

        $reactMsg = WhatsappApiMessage::compose(to: $user->phone)->reactTo(msg: $toBeReactedTo->whatsapp_message_id, with: '👍');
        $result = $this->whatsappService->send($reactMsg, $user);

        $this->assertDatabaseHas('whatsapp_messages', [
            'user_id' => $user->id,
            'whatsapp_message_id' => 'some-whatsapp-msg-id',
            'payload' => json_encode([['type' => MessageType::REACTION, 'emoji' => '👍', 'from' => config('whatsapp.business_phone_number')]]),
        ]);
    }

    public function test__send__it_should_warn_if_reacted_to_message_record_was_not_found(): void {
        Log::spy();

        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-reply-msg-id']]])));

        $user = User::first();
        $reactMsg = WhatsappApiMessage::compose(to: $user->phone)->reactTo(msg: 'missing-whatsapp-msg-id', with: '👍');

        Log::shouldReceive('warning')
            ->once()
            ->with('Message not found on the database: missing-whatsapp-msg-id');

        $result = $this->whatsappService->send($reactMsg, $user);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('messages', $result);
        $this->assertNotEmpty($result['messages']);
        $this->assertDatabaseCount('whatsapp_messages', 0);
    }

    public function test__send__it_should_create_message_record_with_template_data_on_successful_send(): void {
        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-whatsapp-msg-id']]])));

        $user = User::first();
        $apiMsg = WhatsappApiMessage::compose(to: $user->phone)
            ->usingTemplate('some_template_name', 'pt_BR')
            ->withComponent(MessageComponent::BODY, [['text' => 'Whatever']]);

        $result = $this->whatsappService->send($apiMsg, $user);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('messages', $result);
        $this->assertNotEmpty($result['messages']);
        $this->assertDatabaseHas('whatsapp_messages', [
            'user_id' => $user->id,
            'type' => MessageType::TEMPLATE->value,
            'whatsapp_message_id' => 'some-whatsapp-msg-id',
            'text' => null,
            'way' => MessageWay::OUTBOUND->value,
            'payload' => json_encode([[
                'language' => ['code' => 'pt_BR'],
                'name' => 'some_template_name',
                'components' => [
                    [
                        'type' => MessageComponent::BODY->value,
                        'sub_type' => null,
                        'index' => null,
                        'parameters' => [['type' => MessageComponent::TEXT->value, 'parameter_name' => null, 'coupon_code' => null, 'text' => 'Whatever']],
                    ],
                ],
                'type' => MessageType::TEMPLATE->value,
            ]]),
        ]);
    }

    public function test__send__it_should_update_message_record_with_removing_reaction_on_successful_send(): void {
        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-whatsapp-msg-id']]])));

        $user = User::first();
        $apiMsg = WhatsappApiMessage::compose(to: $user->phone)->with(text: 'Some text message');
        $result = $this->whatsappService->send($apiMsg, $user);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('messages', $result);
        $this->assertNotEmpty($result['messages']);

        $toBeReactedTo = WhatsappMessage::first();

        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-reply-msg-id']]])));

        $reactMsg = WhatsappApiMessage::compose(to: $user->phone)->reactTo(msg: $toBeReactedTo->whatsapp_message_id, with: '👍');
        $result = $this->whatsappService->send($reactMsg, $user);

        $this->assertDatabaseHas('whatsapp_messages', [
            'user_id' => $user->id,
            'whatsapp_message_id' => 'some-whatsapp-msg-id',
            'payload' => json_encode([['type' => MessageType::REACTION, 'emoji' => '👍', 'from' => config('whatsapp.business_phone_number')]]),
        ]);

        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(['messages' => [['id' => 'some-reply-msg-id']]])));
        $removeReactMsg = WhatsappApiMessage::compose(to: $user->phone)->reactTo(msg: $toBeReactedTo->whatsapp_message_id, with: '');
        $result = $this->whatsappService->send($removeReactMsg, $user);

        $this->assertDatabaseHas('whatsapp_messages', [
            'user_id' => $user->id,
            'whatsapp_message_id' => 'some-whatsapp-msg-id',
            'payload' => json_encode([]),
        ]);
    }

    public function test__get_message_templates__it_should_return_the_available_message_template_list(): void {
        $this->mock->append(new Response(Res::HTTP_OK, [], json_encode(self::getJsonFixture('Api/Payloads/get-available-templates'))));

        $result = $this->whatsappService->getMessageTemplates();

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('paging', $result);
        $this->assertCount(2, $result['data']);
    }

    public function test__get_message_templates__it_log_whatsapp_api_errors(): void {
        Log::spy();
        Log::shouldReceive('error')
            ->once()
            ->with('Error getting available templates', Mockery::type('array'));

        $this->mock->append(new Response(Res::HTTP_INTERNAL_SERVER_ERROR, [], json_encode(['statusCode' => Res::HTTP_INTERNAL_SERVER_ERROR, 'body' => ['some-key' => 'some value'], 'error' => 'some error message'])));

        $result = $this->whatsappService->getMessageTemplates();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}

<?php

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\{Config, Log};
use PHPUnit\Framework\Attributes\DataProvider;
use The42dx\Whatsapp\Enums\MessageType;
use The42dx\Whatsapp\Services\WhatsappService;
use The42dx\Whatsapp\Tests\Fixtures\Models\User;
use The42dx\Whatsapp\Tests\Integration\IntegrationTestCase;

class WhatsappServiceTest extends IntegrationTestCase {
    protected function setUp(): void {
        parent::setUp();

        User::create(['phone' => '111111111111']);
    }

    public static function yetUnsupportedMsgTypesDataset(): array {
        return [
            MessageType::AUDIO->value => [MessageType::AUDIO, []],
            MessageType::BUTTON->value => [MessageType::BUTTON, []],
            MessageType::CONTACTS->value => [MessageType::CONTACTS, []],
            MessageType::DOCUMENT->value => [MessageType::DOCUMENT, []],
            MessageType::IMAGE->value => [MessageType::IMAGE, []],
            MessageType::INTERACTIVE->value => [MessageType::INTERACTIVE, []],
            MessageType::LOCATION->value => [MessageType::LOCATION, []],
            MessageType::REACTION->value => [MessageType::REACTION, []],
            MessageType::STICKER->value => [MessageType::STICKER, []],
            MessageType::TEMPLATE->value => [MessageType::TEMPLATE, []],
            MessageType::VIDEO->value => [MessageType::VIDEO, []],
        ];
    }

    public function test__construct__it_initializes_service(): void {
        $wppService = new WhatsappService($this->http);

        $this->assertNotNull($wppService);
        $this->assertInstanceOf(WhatsappService::class, $wppService);
    }

    public function test__construct__if_throws_exception_on_invalid_config(): void {
        $this->expectException(\InvalidArgumentException::class);

        Config::set('whatsapp.business_phone_id', null);

        new WhatsappService;
    }

    #[DataProvider('yetUnsupportedMsgTypesDataset')]
    public function test__send__it_logs_warning_on_unsupported_message_type(MessageType $msgType, mixed $data): void {
        Log::spy();

        $this->mock->append(new Response(200));

        Log::shouldReceive('warning')
            ->once()
            ->with('Unsupported message type: ' . $msgType->value);

        $user = User::first();
        $result = $this->whatsappService->send($msgType, $user, $data);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test__send__it_should_shor_circuit_if_user_has_no_phone_number(): void {
        Log::spy();

        $user = User::first();
        $user->phone = null;
        $user->save();

        Log::shouldReceive('warning')
            ->once()
            ->with('User does not have a phone number set', ['user_id' => $user->id]);

        $result = $this->whatsappService->send(MessageType::TEXT, $user, ['text' => 'Hello']);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test__send__it_log_whatsapp_api_errors(): void {
        Log::spy();
        Log::shouldReceive('error')
            ->once()
            ->with('Error sending whatsapp message', Mockery::type('array'));

        $this->mock->append(new Response(422, [], json_encode(['error' => 'some error message'])));

        $user = User::first();
        $result = $this->whatsappService->send(MessageType::TEXT, $user, 'Some text message');

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
        $result = $this->whatsappService->send(MessageType::TEXT, $user, 'Some text message');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test__send__it_creates_message_record_on_successful_send(): void {
        $this->mock->append(new Response(200, [], json_encode(['messages' => [['id' => 'some-whatsapp-msd-id']]])));

        $user = User::first();
        $result = $this->whatsappService->send(MessageType::TEXT, $user, 'Some text message');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('messages', $result);
        $this->assertNotEmpty($result['messages']);

        $this->assertDatabaseHas('whatsapp_messages', [
            'user_id' => $user->id,
            'type' => MessageType::TEXT,
            'whatsapp_message_id' => 'some-whatsapp-msd-id',
            'text' => 'Some text message',
            'way' => 'outbound',
        ]);
    }
}

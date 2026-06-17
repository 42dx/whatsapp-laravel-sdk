<?php

namespace The42dx\Whatsapp\Tests\Integration\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{Config, Log, Schema};
use PHPUnit\Framework\Attributes\DataProvider;
use The42dx\Whatsapp\Enums\{ApiEvent, ContextType, MessageStatus, MessageType, MessageWay};
use The42dx\Whatsapp\Models\WhatsappMessage;
use The42dx\Whatsapp\Tests\Fixtures\Models\User;
use The42dx\Whatsapp\Tests\Integration\IntegrationTestCase;

class WebhookControllerTest extends IntegrationTestCase {
    protected array $validWebhookCheckData;

    protected string $webhookRoute;

    protected string $wppTable;

    private static function getApiEventPlaceholder(ApiEvent $event): array {
        return ['entry' => [['id' => 'placeholder', 'changes' => [['field' => $event]]]]];
    }

    public static function apiEventDataset(): array {
        return [
            ApiEvent::ACC_ALERTS->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::ACC_ALERTS)],
            ApiEvent::ACC_REVIEW_UPDATE->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::ACC_REVIEW_UPDATE)],
            ApiEvent::ACC_UPDT->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::ACC_UPDT)],
            ApiEvent::BUSINESS_CAPABILITY_UPDT->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::BUSINESS_CAPABILITY_UPDT)],
            ApiEvent::BUSINESS_STATUS_UPDT->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::BUSINESS_STATUS_UPDT)],
            ApiEvent::CAMPAIGN_STATUS_UPDT->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::CAMPAIGN_STATUS_UPDT)],
            ApiEvent::FLOWS->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::FLOWS)],
            ApiEvent::MSG_ECHOES->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::MSG_ECHOES)],
            ApiEvent::MSG_HANDOVERS->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::MSG_HANDOVERS)],
            ApiEvent::MSG_TPLT_QUALITY_UPDT->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::MSG_TPLT_QUALITY_UPDT)],
            ApiEvent::MSG_TPLT_STATUS_UPDT->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::MSG_TPLT_STATUS_UPDT)],
            ApiEvent::PARTNER_SOLUTIONS->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::PARTNER_SOLUTIONS)],
            ApiEvent::PHONE_NUM_NAME_UPDT->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::PHONE_NUM_NAME_UPDT)],
            ApiEvent::PHONE_NUM_QUALITY_UPDT->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::PHONE_NUM_QUALITY_UPDT)],
            ApiEvent::SECURITY->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::SECURITY)],
            ApiEvent::TEMPLATE_CAT_UPDT->value . ' API Event' => [self::getApiEventPlaceholder(ApiEvent::TEMPLATE_CAT_UPDT)],
        ];
    }

    public static function msgTypesDataset(): array {
        return [
            MessageType::AUDIO->value => ['Api/Events/message-audio'],
            MessageType::CONTACTS->value => ['Api/Events/message-contact'],
            MessageType::DOCUMENT->value => ['Api/Events/message-document'],
            MessageType::IMAGE->value => ['Api/Events/message-image'],
            MessageType::INTERACTIVE->value => ['Api/Events/message-interactive'],
            MessageType::BUTTON->value => ['Api/Events/message-button'],
            MessageType::LOCATION->value => ['Api/Events/message-location'],
            MessageType::STICKER->value => ['Api/Events/message-sticker'],
            MessageType::VIDEO->value => ['Api/Events/message-video'],
        ];
    }

    protected function setUp(): void {
        parent::setUp();

        $this->webhookRoute = config('whatsapp.webhook_route');
        $this->wppTable = config('whatsapp.database.table_name');

        $this->validWebhookCheckData = [
            'hub_challenge' => '1234567890',
            'hub_mode' => 'subscribe',
            'hub_verify_token' => config('whatsapp.webhook_verify'),
        ];
    }

    public function test__check__it_should_handle_whatsapp_webhook_check(): void {
        $queryData = http_build_query($this->validWebhookCheckData);

        $this->getJson($this->webhookRoute . "?{$queryData}")
            ->assertOk()
            ->assertSee('1234567890');
    }

    public function test__check__it_should_return_error_if_hub_mode_check_fails(): void {
        $queryData = http_build_query(
            array_merge(
                $this->validWebhookCheckData,
                ['hub_mode' => 'not-valid'],
            )
        );

        $this->getJson($this->webhookRoute . "?{$queryData}")
            ->assertUnprocessable()
            ->assertJsonFragment(['message' => 'Invalid hub mode']);
    }

    public function test__check__it_should_return_error_if_verify_token_check_fails(): void {
        $queryData = http_build_query(
            array_merge(
                $this->validWebhookCheckData,
                ['hub_verify_token' => 'invalid-token'],
            )
        );

        $this->getJson($this->webhookRoute . "?{$queryData}")
            ->assertUnprocessable()
            ->assertJsonFragment(['message' => 'Invalid verify token']);
    }

    #[DataProvider('apiEventDataset')]
    public function test__handle__unhandled_api_event(array $apiEventFixture = []): void {
        // This is here just to greenlight code coverage on the switch/cases
        $this->postJson($this->webhookRoute, $apiEventFixture)
            ->assertOk();
    }

    #[DataProvider('msgTypesDataset')]
    public function test__handle__unhandled_msg_types(string $jsonFixture): void {
        // This is here just to greenlight code coverage on the switch/cases
        $this->postJson($this->webhookRoute, self::getJsonFixture($jsonFixture))
            ->assertOk();
    }

    public function test__handle__it_should_add_the_messageable_id_to_message_record_when_messageable_exists_in_the_system(): void {
        $user = User::create(['phone' => '16315551181']);

        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-text'))
            ->assertOk();

        $this->assertDatabaseCount($this->wppTable, 1);
        $this->assertDatabaseCount('users', 1);

        $this->assertEquals(WhatsappMessage::first()->{config('whatsapp.database.messageable_id_column')}, $user->id);
    }

    public function test__handle__it_should_use_configured_messageable_id_column(): void {
        Config::set('whatsapp.database.messageable_id_column', 'customer_id');

        Schema::table($this->wppTable, function(Blueprint $table): void {
            $table->unsignedBigInteger('customer_id')->nullable();
        });

        $user = User::create(['phone' => '16315551181']);

        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-text'))
            ->assertOk();

        $this->assertDatabaseHas($this->wppTable, [
            'customer_id' => $user->id,
        ]);
    }

    public function test__handle__it_should_handle_messages_status(): void {
        $fixture = self::getJsonFixture('Api/Events/status-change');
        $wppMsgId = $fixture['entry'][0]['changes'][0]['value']['statuses'][0]['id'];

        WhatsappMessage::factory(['whatsapp_message_id' => $wppMsgId])
            ->withStatus(MessageStatus::SENT)->create();

        $this->assertDatabaseCount($this->wppTable, 1);
        $this->postJson($this->webhookRoute, $fixture)
            ->assertOk();

        $this->assertDatabaseCount($this->wppTable, 1);
        $this->assertEquals(MessageStatus::DELIVERED->value, WhatsappMessage::first()->status);
    }

    public function test__handle__it_should_warn_if_status_to_be_updated_msg_was_not_found_on_the_database(): void {
        Log::spy();

        Log::shouldReceive('warning')
            ->once()
            ->with('Message not found on the database: wamid.HBgMNTU0MTg0MTEyNjc5FQIAERgSQzEwNjgwQzQ0OTIxQjk2Qjk3AA==');

        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/status-change'))
            ->assertOk();
    }

    public function test__handle__it_should_handle_text_messages(): void {
        $this->assertDatabaseCount($this->wppTable, 0);
        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-text'))
            ->assertOk();

        $msg = WhatsappMessage::first();
        $this->assertDatabaseCount($this->wppTable, 1);
        $this->assertEquals('Some message', $msg->text);
        $this->assertEquals(MessageWay::INBOUND->value, $msg->way);
    }

    public function test__handle__it_should_handle_reply_context_messages(): void {
        $this->assertDatabaseCount($this->wppTable, 0);
        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-reply-context'))
            ->assertOk();

        $this->assertDatabaseCount($this->wppTable, 1);

        $msg = WhatsappMessage::first();

        $this->assertEquals('Some reply', $msg->text);
        $this->assertEquals('some-message-id', $msg->payload[0]['context']);
        $this->assertEquals(MessageWay::INBOUND->value, $msg->way);
        $this->assertEquals(ContextType::REPLY->value, $msg->payload[0]['type']);
    }

    public function test__handle__it_should_handle_forwarded_messages(): void {
        $this->assertDatabaseCount($this->wppTable, 0);
        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-forwarded-context'))
            ->assertOk();

        $msg = WhatsappMessage::first();

        $this->assertDatabaseCount($this->wppTable, 1);
        $this->assertNull($msg->payload[0]['context']);
        $this->assertEquals(MessageWay::INBOUND->value, $msg->way);
        $this->assertEquals(ContextType::FWD->value, $msg->payload[0]['type']);
    }

    public function test__handle__it_should_handle_frequently_forwarded_messages(): void {
        $this->assertDatabaseCount($this->wppTable, 0);
        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-frequently-forwarded-context'))
            ->assertOk();

        $msg = WhatsappMessage::first();

        $this->assertDatabaseCount($this->wppTable, 1);
        $this->assertNull($msg->payload[0]['context']);
        $this->assertEquals(MessageWay::INBOUND->value, $msg->way);
        $this->assertEquals(ContextType::F_FWD->value, $msg->payload[0]['type']);
    }

    public function test__handle__it_should_handle_add_reaction_to_messages(): void {
        $this->assertDatabaseCount($this->wppTable, 0);
        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-text'))
            ->assertOk();

        $msg = WhatsappMessage::first();

        $this->assertDatabaseCount($this->wppTable, 1);

        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-reaction'))
            ->assertOk();

        $this->assertDatabaseCount($this->wppTable, 1);

        $msg = $msg->refresh();

        $this->assertCount(1, $msg->payload);
        $this->assertArrayHasKey('from', $msg->payload[0]);
        $this->assertArrayHasKey('emoji', $msg->payload[0]);
        $this->assertArrayHasKey('type', $msg->payload[0]);
        $this->assertEquals(MessageType::REACTION->value, $msg->payload[0]['type']);
        $this->assertEquals('111111111111', $msg->payload[0]['from']);
        $this->assertEquals('❤️', $msg->payload[0]['emoji']);

        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-reaction-2'))
            ->assertOk();

        $this->assertDatabaseCount($this->wppTable, 1);

        $msg = $msg->refresh();

        $this->assertCount(2, $msg->payload);
        $this->assertArrayHasKey('from', $msg->payload[1]);
        $this->assertArrayHasKey('emoji', $msg->payload[1]);
        $this->assertArrayHasKey('type', $msg->payload[1]);
        $this->assertEquals(MessageType::REACTION->value, $msg->payload[1]['type']);
        $this->assertEquals('333333333333', $msg->payload[1]['from']);
        $this->assertEquals('❤️', $msg->payload[1]['emoji']);
    }

    public function test__handle__it_should_handle_remove_reaction_to_messages(): void {
        $this->assertDatabaseCount($this->wppTable, 0);
        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-text'))
            ->assertOk();

        $msg = WhatsappMessage::first();

        $this->assertDatabaseCount($this->wppTable, 1);

        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-reaction'))
            ->assertOk();

        $msg = $msg->refresh();

        $this->assertDatabaseCount($this->wppTable, 1);
        $this->assertCount(1, $msg->payload);
        $this->assertArrayHasKey('from', $msg->payload[0]);
        $this->assertArrayHasKey('emoji', $msg->payload[0]);
        $this->assertArrayHasKey('type', $msg->payload[0]);
        $this->assertEquals(MessageType::REACTION->value, $msg->payload[0]['type']);
        $this->assertEquals('111111111111', $msg->payload[0]['from']);
        $this->assertEquals('❤️', $msg->payload[0]['emoji']);

        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-reaction-2'))
            ->assertOk();

        $this->assertDatabaseCount($this->wppTable, 1);

        $msg = $msg->refresh();

        $this->assertCount(2, $msg->payload);
        $this->assertArrayHasKey('from', $msg->payload[1]);
        $this->assertArrayHasKey('emoji', $msg->payload[1]);
        $this->assertArrayHasKey('type', $msg->payload[1]);
        $this->assertEquals(MessageType::REACTION->value, $msg->payload[1]['type']);
        $this->assertEquals('333333333333', $msg->payload[1]['from']);
        $this->assertEquals('❤️', $msg->payload[1]['emoji']);

        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-reaction-remove'))
            ->assertOk();

        $this->assertDatabaseCount($this->wppTable, 1);

        $msg = $msg->refresh();

        $this->assertCount(1, $msg->payload);
        $this->assertCount(1, $msg->payload);
        $this->assertArrayHasKey('from', $msg->payload[0]);

        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-reaction-2-remove'))
            ->assertOk();

        $this->assertDatabaseCount($this->wppTable, 1);

        $msg = $msg->refresh();

        $this->assertCount(0, $msg->payload);
    }

    public function test__handle__it_should_warn_if_reacted_to_msg_was_not_found_on_the_database(): void {
        Log::spy();

        Log::shouldReceive('warning')
            ->once()
            ->with('Message not found on the database: ABGGFlA5Fpa');

        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-reaction'))
            ->assertOk();
    }

    public function test__handle__todo_it_should_handle_clicked_button_on_template_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_audio_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_contact_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_document_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_image_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_location_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_sticker_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_video_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}

<?php

namespace The42dx\Whatsapp\Tests\Integration\Http\Controllers;

use PHPUnit\Framework\Attributes\DataProvider;
use The42dx\Whatsapp\Enums\{ApiEvent, MessageStatus, MessageType};
use The42dx\Whatsapp\Models\WhatsappMessage;
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
            MessageType::REACTION->value => ['Api/Events/message-reaction'],
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

    public function test__handle__it_should_handle_messages_status(): void {
        $fixture = self::getJsonFixture('Api/Events/status-change');
        $wppMsgId = $fixture['entry'][0]['changes'][0]['value']['statuses'][0]['id'];

        WhatsappMessage::factory(['whatsapp_message_id' => $wppMsgId])
            ->withStatus(MessageStatus::SENT)->create();

        $this->assertDatabaseCount($this->wppTable, 1);
        $this->postJson($this->webhookRoute, $fixture)
            ->assertOk();

        $this->assertDatabaseCount($this->wppTable, 1);
        $this->assertEquals(WhatsappMessage::first()->status, MessageStatus::DELIVERED->value);
    }

    public function test__handle__it_should_handle_text_messages(): void {
        $this->assertDatabaseCount($this->wppTable, 0);
        $this->postJson($this->webhookRoute, self::getJsonFixture('Api/Events/message-text'))
            ->assertOk();

        $this->assertDatabaseCount($this->wppTable, 1);

        $this->assertEquals(WhatsappMessage::first()->text, 'Some message');
    }

    public function test__handle__todo_it_should_handle_audio_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_contact_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_forwarded_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_frequently_forwarded_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_replied_messages(): void {
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

    public function test__handle__todo_it_should_handle_reaction_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_sticker_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function test__handle__todo_it_should_handle_video_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}

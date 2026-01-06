<?php

namespace The42dx\Whatsapp\Tests\Integration\Http\Controllers;

use PHPUnit\Framework\Attributes\DataProvider;
use The42dx\Whatsapp\Enums\ApiEvent;
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
            ApiEvent::MSGS->value . ' API Event' => [self::getJsonFixture('Api/Events/message-text')],
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
    public function test__handle__it_should_handle_text_messages(array $apiEventFixture = []): void {
        $this->postJson($this->webhookRoute, $apiEventFixture)
            ->assertOk();

        // remove the condition when all other message types are implemented
        if ($apiEventFixture['entry'][0]['changes'][0]['field'] !== ApiEvent::MSGS->value) {
            return;
        }

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

    public function test__handle__todo_it_should_handle_frequentlyforwarded_messages(): void {
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

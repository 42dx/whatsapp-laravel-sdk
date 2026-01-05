<?php

namespace The42dx\Whatsapp\Tests\Integration\Http\Controllers;

use Illuminate\Support\Facades\Config;
use The42dx\Whatsapp\Tests\Integration\IntegrationTestCase;

class WebhookControllerTest extends IntegrationTestCase {
    protected array $validWebhookCheckData;

    protected string $webhookRoute;

    protected function setUp(): void {
        parent::setUp();

        Config::set('whatsapp.webhook_verify', env('WPP_WEBHOOK_VERIFY'));
        Config::set('whatsapp.webhook_route', env('WPP_WEBHOOK_ROUTE'));

        $this->validWebhookCheckData = [
            'hub_challenge' => '1234567890',
            'hub_mode' => 'subscribe',
            'hub_verify_token' => config('whatsapp.webhook_verify'),
        ];
    }

    public function test__check__it_should_handle_whatsapp_webhook_check(): void {
        $queryData = http_build_query($this->validWebhookCheckData);

        $this->getJson(config('whatsapp.webhook_route') . "?{$queryData}")
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

        $this->getJson(config('whatsapp.webhook_route') . "?{$queryData}")
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

        $this->getJson(config('whatsapp.webhook_route') . "?{$queryData}")
            ->assertUnprocessable()
            ->assertJsonFragment(['message' => 'Invalid verify token']);
    }

    public function test__handle__todo_it_should_handle_text_messages(): void {
        $this->markTestIncomplete('This test has not been implemented yet.');
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

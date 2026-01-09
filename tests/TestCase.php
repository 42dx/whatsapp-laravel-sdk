<?php

namespace The42dx\Whatsapp\Tests;

use GuzzleHttp\{Client, HandlerStack};
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Support\Facades\Config;
use Mockery;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use The42dx\Whatsapp\Services\WhatsappService;
use The42dx\Whatsapp\Tests\Fixtures\Models\User;

abstract class TestCase extends TestbenchTestCase {
    protected MockHandler $mock;

    protected Client $http;

    protected WhatsappService $whatsappService;

    protected function setUp(): void {
        parent::setUp();

        Config::set('whatsapp.webhook_verify', env('WPP_WEBHOOK_VERIFY'));
        Config::set('whatsapp.webhook_route', env('WPP_WEBHOOK_ROUTE'));
        Config::set('whatsapp.database.user_model', User::class);
        Config::set('whatsapp.database.table_name', 'whatsapp_messages');
        Config::set('whatsapp.database.users_table_pk', 'id');
        Config::set('whatsapp.database.users_table', 'users');
        Config::set('whatsapp.database.user_phone_column', 'phone');
        Config::set('whatsapp.database.messageable_id_column', 'user_id');
        Config::set('whatsapp.server_url', env('WPP_SERVER_URL'));
        Config::set('whatsapp.api_version', env('WPP_API_VERSION'));
        Config::set('whatsapp.business_phone_id', env('WPP_BUSINESS_PHONE_ID'));
        Config::set('whatsapp.token', env('WPP_TOKEN'));

        $this->mock = new MockHandler([]);
        $this->http = new Client(['handler' => HandlerStack::create($this->mock)]);
        $this->whatsappService = new WhatsappService($this->http);
    }

    protected static function getJsonFixture(string $filename): array {
        return json_decode(file_get_contents(__DIR__ . "/Fixtures/{$filename}.json"), true);
    }

    protected function tearDown(): void {
        $this->mock->reset();
        Mockery::close();
        parent::tearDown();
    }
}

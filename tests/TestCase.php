<?php

namespace The42dx\Whatsapp\Tests;

use Illuminate\Support\Facades\Config;
use Mockery;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use The42dx\Whatsapp\Tests\Fixtures\Models\User;

abstract class TestCase extends TestbenchTestCase {
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
    }

    protected static function getJsonFixture(string $filename): array {
        return json_decode(file_get_contents(__DIR__ . "/Fixtures/{$filename}.json"), true);
    }

    protected function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }
}

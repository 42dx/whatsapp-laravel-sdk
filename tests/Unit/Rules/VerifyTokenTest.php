<?php

namespace The42dx\Whatsapp\Tests\Unit\Rules;

use Illuminate\Support\Facades\Config;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;
use The42dx\Whatsapp\Rules\VerifyToken;

class VerifyTokenTest extends UnitTestCase {
    protected function setUp(): void {
        parent::setUp();

        Config::set('whatsapp.webhook_verify', env('WPP_WEBHOOK_VERIFY'));
    }

    public function test_itShouldFailIfProvidedTokenIsInvalid() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid verify token');

        $rule = new VerifyToken();
        $rule->validate('hub_verify_token', 'invalid_token', fn($message) => throw new \Exception($message));
    }

    public function test_itShouldPassIfProvidedTokenIsValid() {
        $this->expectNotToPerformAssertions();

        $rule = new VerifyToken();
        $rule->validate('hub_verify_token', 'some-verify', fn($message) => throw new \Exception($message));
    }

    protected function tearDown(): void {
        parent::tearDown();

        Config::set('whatsapp.webhook_verify', null);
    }
}

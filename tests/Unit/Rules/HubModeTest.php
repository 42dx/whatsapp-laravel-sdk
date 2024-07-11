<?php

namespace The42dx\Whatsapp\Tests\Unit\Rules;

use The42dx\Whatsapp\Tests\Unit\UnitTestCase;
use The42dx\Whatsapp\Rules\HubMode;

class HubModeTest extends UnitTestCase {
    public function test_itShouldFailIfProvidedHubModeIsNotSubscribe() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid hub mode');

        $rule = new HubMode();
        $rule->validate('hub_mode', 'invalid_mode', fn($message) => throw new \Exception($message));
    }

    public function test_itShouldPassIfProvidedHubModeIsSubscribe() {
        $this->expectNotToPerformAssertions();

        $rule = new HubMode();
        $rule->validate('hub_mode', 'subscribe', fn($message) => throw new \Exception($message));
    }
}

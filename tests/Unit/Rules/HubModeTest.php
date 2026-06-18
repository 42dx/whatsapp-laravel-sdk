<?php

namespace The42dx\Whatsapp\Tests\Unit\Rules;

use Exception;
use The42dx\Whatsapp\Rules\HubMode;
use The42dx\Whatsapp\Tests\Unit\UnitTestCase;

class HubModeTest extends UnitTestCase {
    public function test_it_should_fail_if_provided_hub_mode_is_not_subscribe(): void {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid hub mode');

        $rule = new HubMode;
        $rule->validate('hub_mode', 'invalid_mode', fn($message) => throw new Exception($message));
    }

    public function test_it_should_pass_if_provided_hub_mode_is_subscribe(): void {
        $this->expectNotToPerformAssertions();

        $rule = new HubMode;
        $rule->validate('hub_mode', 'subscribe', fn($message) => throw new Exception($message));
    }
}

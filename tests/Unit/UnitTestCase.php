<?php

namespace The42dx\Whatsapp\Tests\Unit;

use Orchestra\Testbench\TestCase;

class UnitTestCase extends TestCase {
    protected function setUp(): void {
        parent::setUp();
    }

    protected function getJsonFixture(string $filename): array {
        return json_decode(file_get_contents(__DIR__ . "/../Fixtures/{$filename}.json"), true);
    }
}

<?php

namespace The42dx\Whatsapp\Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase {
    protected function setUp(): void {
        parent::setUp();
    }

    protected function getJsonFixture(string $filename): array {
        return json_decode(file_get_contents(__DIR__ . "/Fixtures/{$filename}.json"), true);
    }
}

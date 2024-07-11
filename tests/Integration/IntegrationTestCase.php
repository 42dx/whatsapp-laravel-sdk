<?php

namespace The42dx\Whatsapp\Tests\Integration;

use Illuminate\Support\Facades\Route;
use The42dx\Whatsapp\Tests\TestCase;

abstract class IntegrationTestCase extends TestCase {
    const CONTROLLER_NAMESPACE = 'The42dx\Http\Controllers';

    public function setUp(): void {
        parent::setUp();

        Route::namespace(self::CONTROLLER_NAMESPACE)
             ->group(__DIR__ . '/../../routes/api.php');
    }
}

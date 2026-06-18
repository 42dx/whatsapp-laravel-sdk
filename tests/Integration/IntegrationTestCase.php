<?php

namespace The42dx\Whatsapp\Tests\Integration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Route, Schema};
use The42dx\Whatsapp\Tests\TestCase;

abstract class IntegrationTestCase extends TestCase {
    use RefreshDatabase;

    public const CONTROLLER_NAMESPACE = 'The42dx\Http\Controllers';

    protected function setUp(): void {
        parent::setUp();

        Schema::create('users', function(Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        Route::namespace(self::CONTROLLER_NAMESPACE)
            ->group(__DIR__ . '/../../routes/api.php');
    }

    protected function tearDown(): void {
        parent::tearDown();
    }
}

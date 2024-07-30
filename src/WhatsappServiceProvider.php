<?php

namespace The42dx\Whatsapp;

use Illuminate\Support\ServiceProvider;
use The42dx\Whatsapp\Services\WhatsappService;

class WhatsappServiceProvider extends ServiceProvider {
    const SERVICE_NAME       = 'whatsapp';
    const CONFIG_FILENAME    = self::SERVICE_NAME . '.php';
    const CONFIG_PATH        = __DIR__ . '/../config/'. self::CONFIG_FILENAME;
    const MIGRATIONS_PATH    = __DIR__ . '/../database/migrations';
    const PUBLISH_TAG_PREFIX = 'whatsapp-business-api-';

    public function register() {
        $this->mergeConfigFrom(self::CONFIG_PATH, self::SERVICE_NAME);

        $this->app->singleton(WhatsappService::class, function () {
            return new WhatsappService();
        });
    }

    public function boot() {
        $this->publishes([self::CONFIG_PATH => config_path(self::CONFIG_FILENAME)],  self::PUBLISH_TAG_PREFIX . 'config');
        $this->publishesMigrations([self::MIGRATIONS_PATH => database_path('migrations')], self::PUBLISH_TAG_PREFIX . 'migrations');

        if (!config('whatsapp.database.skip_migrations')) {
            $this->loadMigrationsFrom(self::MIGRATIONS_PATH);
        }
    }
}

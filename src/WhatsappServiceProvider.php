<?php

namespace The42dx\Whatsapp;

use Illuminate\Support\ServiceProvider;

class WhatsappServiceProvider extends ServiceProvider {
    const SERVICE_NAME = 'whatsapp';

    public function register() {
        $this->app->bind(self::SERVICE_NAME, function () {
            // return new Whatsapp();
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/'. self::SERVICE_NAME . '.php', self::SERVICE_NAME);
    }

    public function boot() {

    }
}

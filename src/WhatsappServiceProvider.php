<?php

namespace The42dx\Whatsapp;

use Illuminate\Support\ServiceProvider;

class WhatsappServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('whatsapp', function () {
            // return new Whatsapp();
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/whatsapp.php', 'whatsapp');
    }

    public function boot() {

    }
}

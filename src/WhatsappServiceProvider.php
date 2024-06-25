<?php

namespace The42dx\Whatsapp;

use Illuminate\Support\ServiceProvider;

class WhatsappServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('whatsapp', function () {
            // return new Whatsapp();
        });
    }

    public function boot() {

    }
}
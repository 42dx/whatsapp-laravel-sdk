<?php

namespace The42dx\Whatsapp;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {
    protected $namespace = 'The42dx\Whatsapp\Http\Controllers';

    public function map(): void {
        Route::namespace($this->namespace)
            ->group(__DIR__ . '/../routes/api.php');
    }
}

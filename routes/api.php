<?php

use Illuminate\Support\Facades\Route;
use The42dx\Whatsapp\Http\Controllers\WebhookController;

Route::group(['prefix' => 'webhook'], function () {
    Route::get('whatsapp', [WebhookController::class, 'check']);
    Route::post('whatsapp', [WebhookController::class, 'handle']);
});

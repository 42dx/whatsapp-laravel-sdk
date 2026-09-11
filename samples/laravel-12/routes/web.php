<?php

use App\Models\User;
use App\Notifications\Not;
use Illuminate\Support\Facades\Route;
use The42dx\Whatsapp\Enums\MessageType;


Route::get('/', fn () => view('welcome'));
Route::get('/asd', function () {
    /** @var User $user */
    $user = User::first();

    $user->notify(new Not());
});

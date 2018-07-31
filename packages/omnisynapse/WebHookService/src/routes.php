<?php

use \Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:jwt'], function () {
    Route::resource('/webhooks', \OmniSynapse\WebHookService\Http\Controllers\WebHookController::class, ['except' => [
        'create', 'edit'
    ]]);

    Route::get('/webhook_events',
        \OmniSynapse\WebHookService\Http\Controllers\WebHookEventController::class . '@index');
});

<?php

$router = app()->make(\Illuminate\Contracts\Routing\Registrar::class);

$router->group(['middleware' => 'auth:jwt'], function () use ($router) {
    $router->resource('/webhooks', \OmniSynapse\WebHookService\Http\Controllers\WebHookController::class, ['except' => [
        'create', 'edit'
    ]]);

    $router->get('/webhook_events',
        \OmniSynapse\WebHookService\Http\Controllers\WebHookEventController::class . '@index');
});

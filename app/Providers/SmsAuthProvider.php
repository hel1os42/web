<?php

namespace App\Providers;

use App\Helpers\SmsAuth;
use Illuminate\Support\ServiceProvider;
use App\Helpers\StubSmsAuth;

class SmsAuthProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmsAuth::class, function () {
            return new StubSmsAuth();
        });
    }
}

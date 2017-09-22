<?php

namespace App\Services\Auth\Otp\Stub;

use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Support\ServiceProvider;

class OtpAuthProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(OtpAuth::class, function () {
            return new StubOtpAuth();
        });
    }
}

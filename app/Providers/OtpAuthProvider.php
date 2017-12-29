<?php

namespace App\Providers;

use App\Services\Auth\Otp\OtpAuth;
use App\Services\Auth\Otp\SendPulseOtpAuth\SendPulseOtpAuth;
use Illuminate\Support\ServiceProvider;

class OtpAuthProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(OtpAuth::class, function () {
            return new SendPulseOtpAuth();
        });
    }
}

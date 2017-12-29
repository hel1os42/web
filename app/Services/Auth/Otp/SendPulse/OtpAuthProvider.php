<?php

namespace App\Services\Auth\Otp\SendPulse;

use App\Services\Auth\Otp\OtpAuth;
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

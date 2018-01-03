<?php

namespace App\Providers;

use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Support\ServiceProvider;

class OtpAuthProvider extends ServiceProvider
{

    public function register()
    {
        $gateClasses = config('otp.gate_class');
        $this->app->singleton(OtpAuth::class, $gateClasses[config('otp.gate')]);
    }
}

<?php

namespace App\Providers;

use App\Services\Auth\Otp\OtpAuth;
use App\Services\NauOffersService;
use App\Services\OffersService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Contracts\Validation\Factory $validator)
    {
        Relation::morphMap([
            'users' => \App\Models\User::class
        ]);

        $validator->extend('otp', function ($attribute, $value, $parameters, Validator $validator) {
            $phone = $validator->getData()['phone'] ?? null;
            if (null === $phone) {
                return false;
            }

            /** @var OtpAuth $otpAuth */
            $otpAuth = app(OtpAuth::class);

            return $otpAuth->validateCode($phone, $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OffersService::class, NauOffersService::class);
    }
}

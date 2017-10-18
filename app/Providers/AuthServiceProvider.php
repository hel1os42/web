<?php

namespace App\Providers;

use App\Services\Auth\Guards\JwtGuard;
use App\Services\Auth\Guards\OtpGuard;
use App\Services\Auth\UsersProviders\OtpEloquentUserProvider;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\NauModels\Offer::class      => \App\Policies\OfferPolicy::class,
        \App\Models\User::class                 => \App\Policies\UserPolicy::class,
        \App\Models\Place::class                => \App\Policies\PlacePolicy::class,
        \App\Models\NauModels\Redemption::class => \App\Policies\RedemptionPolicy::class,
        \App\Models\ActivationCode::class       => \App\Policies\ActivationCodePolicy::class,
        \App\Models\Category::class             => \App\Policies\CategoryPolicy::class,
        \App\Models\NauModels\Transact::class   => \App\Policies\TransactPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /** @var AuthManager $authManager */
        $authManager = $this->app->make('auth');

        $authManager->provider('otp-eloquent', function ($app, array $config) {
            return new OtpEloquentUserProvider($app['hash'], $config['model']);
        });

        /** @SuppressWarnings(PHPMD.UnusedLocalVariable) */
        $authManager->extend('jwt', function ($app, $name, array $config) use ($authManager) {
            return new JwtGuard($authManager->createUserProvider($config['provider']));
        });

        /** @SuppressWarnings(PHPMD.UnusedLocalVariable) */
        $authManager->extend('otp', function ($app, $name, array $config) use ($authManager) {
            return new OtpGuard($authManager->createUserProvider($config['provider']));
        });
    }
}

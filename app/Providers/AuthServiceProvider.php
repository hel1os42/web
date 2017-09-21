<?php

namespace App\Providers;

use App\Services\Auth\SmsGuard;
use App\Http\Auth\JwtGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('jwt-driver', function ($app, $name, array $config) {
            return new JwtGuard(Auth::createUserProvider($config['provider']));
        });

        Auth::extend('sms-driver', function ($app, $name, array $config) {
            return new SmsGuard($name,
                \Auth::createUserProvider($config['provider']),
                $app['session.store']
            );
        });
    }
}

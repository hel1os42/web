<?php

namespace App\Providers;

use App\Services\Auth\SmsGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Auth::extend('session', function ($app, $name, array $config) {
            return new SmsGuard($name,
                \Auth::createUserProvider($config['provider']),
                $app['session.store']
            );
        });
    }
}

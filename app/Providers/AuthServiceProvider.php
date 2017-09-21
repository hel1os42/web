<?php

namespace App\Providers;

use App\Services\Auth\JwtGuard;
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
    }
}

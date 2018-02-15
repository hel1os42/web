<?php

namespace App\Providers;

use App\Services\Auth\Guards\JwtGuard;
use App\Services\Auth\Guards\OtpGuard;
use App\Services\Auth\UsersProviders\OtpEloquentUserProvider;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //\App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * @var array
     */
    private $abilities = [
        'activation_codes.show' => 'ActivationCodePolicy@show',

        'categories.list'          => 'CategoryPolicy@index',
        'categories.show'          => 'CategoryPolicy@show',
        'categories.create'        => 'CategoryPolicy@create',
        'categories.update'        => 'CategoryPolicy@update',
        'categories.picture.store' => 'CategoryPolicy@pictureStore',

        'tags.list'   => 'TagPolicy@index',
        'tags.show'   => 'TagPolicy@show',
        'tags.create' => 'TagPolicy@create',
        'tags.update' => 'TagPolicy@update',

        'offers.list' => 'OfferPolicy@index',
        'offers.show' => 'OfferPolicy@show',

        'operators.list'   => 'OperatorPolicy@index',
        'operators.show'   => 'OperatorPolicy@show',
        'operators.create' => 'OperatorPolicy@create',
        'operators.delete' => 'OperatorPolicy@destroy',
        'operators.update' => 'OperatorPolicy@update',

        'my.offers.list'       => 'OfferPolicy@indexMy',
        'my.offer.show'        => 'OfferPolicy@showMy',
        'offers.create'        => 'OfferPolicy@create',
        'offers.update'        => 'OfferPolicy@update',
        'offers.delete'        => 'OfferPolicy@destroy',
        'offers.picture.store' => 'OfferPolicy@pictureStore',

        'offers.picture.store.byOfferData' => 'OfferPolicy@pictureStoreByOfferData',

        'places.list'          => 'PlacePolicy@index',
        'places.show'          => 'PlacePolicy@show',
        'places.offers.list'   => 'PlacePolicy@showOffers',
        'places.create'        => 'PlacePolicy@create',
        'places.update'        => 'PlacePolicy@update',
        'places.picture.store' => 'PlacePolicy@pictureStore',

        'offers.redemption'         => 'RedemptionPolicy@index',
        'offers.redemption.confirm' => 'RedemptionPolicy@confirm',
        'offers.redemption.show'    => 'RedemptionPolicy@show',

        'roles.list' => 'RolePolicy@index',
        'roles.show' => 'RolePolicy@show',

        'transactions.list'          => 'TransactPolicy@index',
        'transactions.create'        => 'TransactPolicy@create',
        'transactions.create.no_fee' => 'TransactPolicy@createNoFee',
        'transaction.show'           => 'TransactPolicy@show',

        'users.create'         => 'UserPolicy@create',
        'users.list'           => 'UserPolicy@index',
        'users.show'           => 'UserPolicy@show',
        'users.update'         => 'UserPolicy@update',
        'users.referrals.list' => 'UserPolicy@referrals',
        'users.picture.store'  => 'UserPolicy@pictureStore',
        'user.update.children' => 'UserPolicy@updateChildren',
        'user.update.parents'  => 'UserPolicy@updateParents',
        'user.update.roles'    => 'UserPolicy@updateRoles',
        'impersonate'          => 'UserPolicy@impersonate',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @param Gate $gate
     */
    public function boot(Gate $gate)
    {
        $this->registerPolicies();

        foreach ($this->abilities as $ability => $callback) {
            $gate->define($ability, '\\App\\Policies\\' . $callback);
        }

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

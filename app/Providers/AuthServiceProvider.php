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
        \App\Models\NauModels\Offer::class      => \App\Policies\OfferPolicy::class,
        \App\Models\User::class                 => \App\Policies\UserPolicy::class,
        \App\Models\Place::class                => \App\Policies\PlacePolicy::class,
        \App\Models\NauModels\Redemption::class => \App\Policies\RedemptionPolicy::class,
        \App\Models\ActivationCode::class       => \App\Policies\ActivationCodePolicy::class,
        \App\Models\Category::class             => \App\Policies\CategoryPolicy::class,
        \App\Models\NauModels\Transact::class   => \App\Policies\TransactPolicy::class,
        \App\Models\Role::class                 => \App\Policies\RolePolicy::class,
    ];

    /**
     * @var array
     */
    private $abilities = [
        'activation_codes.show' => 'ActivationCodePolicy@show',

        'categories.list' => 'CategoryPolicy@index',
        'categories.show' => 'CategoryPolicy@show',

        'offers.list' => 'OfferPolicy@index',
        'offers.show' => 'OfferPolicy@show',

        'my.offers.list'          => 'OfferPolicy@indexMy',
        'my.offers.show'          => 'OfferPolicy@showMy',
        'my.offers.create'        => 'OfferPolicy@create',
        'my.offers.store'         => 'OfferPolicy@store',
        'my.offers.update'        => 'OfferPolicy@update',
        'my.offers.picture.store' => 'OfferPolicy@pictureStore',

        'places.list'          => 'PlacePolicy@index',
        'places.show'          => 'PlacePolicy@show',
        'my.places.show'       => 'PlacePolicy@showMy',
        'places.offers.list'   => 'PlacePolicy@showOffers',
        'places.create'        => 'PlacePolicy@create',
        'places.store'         => 'PlacePolicy@store',
        'places.update'        => 'PlacePolicy@update',
        'places.picture.store' => 'PlacePolicy@pictureStore',

        'redemption.code'              => 'RedemptionPolicy@code',
        'redemption.create.from_offer' => 'RedemptionPolicy@createFromOffer',
        'redemption.create'            => 'RedemptionPolicy@create',
        'redemption.store'             => 'RedemptionPolicy@store',
        'redemption.redeem'            => 'RedemptionPolicy@redeem',
        'redemption.show'              => 'RedemptionPolicy@show',
        'redemption.show.from_offer'   => 'RedemptionPolicy@showFromOffer',

        'roles.list' => 'RolePolicy@index',
        'roles.show' => 'RolePolicy@show',

        'transactions.list'     => 'TransactPolicy@index',
        'transactions.create'   => 'TransactPolicy@create',
        'transactions.complete' => 'TransactPolicy@complete',

        'users.list'            => 'UserPolicy@index',
        'users.show'            => 'UserPolicy@show',
        'users.update'          => 'UserPolicy@update',
        'users.referrals.list'  => 'UserPolicy@referrals',
        'users.picture.store'   => 'UserPolicy@pictureStore',
        'users.update.children' => 'UserPolicy@updateChildren',
        'users.update.parents'  => 'UserPolicy@updateParents',
        'users.update.roles'    => 'UserPolicy@updateRoles',

        'picture.show.public' => 'UserPolicy@pictureShow',
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
            $gate->define($ability, $callback);
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

<?php

/** @var \Illuminate\Routing\Router $router */
$router = app('router');

// Unauthorized users
$router->group(['middleware' => 'guest:jwt,web'], function () use ($router) {

    $router->get('/', function () {
        return response()->render('home', []);
    })->name('home');

    $router->group(['prefix' => 'auth'], function () use ($router) {

        $router->group(['prefix' => 'login'], function () use ($router) {
            $router->get('', 'Auth\LoginController@getLogin')
                   ->name('loginForm');

            $router->post('', 'Auth\LoginController@login')
                   ->name('login');

            $router->get('{phone_number}/code', 'Auth\LoginController@getOtpCode')
                   ->middleware(['throttle:1,1'])
                   ->where('phone_number', '\+[0-9]+')
                   ->name('get-login-otp-code');
        });

        $router->group(['prefix' => 'register'], function () use ($router) {
            $router->get('{invite}/{phone_number}/code', 'Auth\RegisterController@getOtpCode')
                   ->middleware(['throttle:1,1'])
                   ->where(['invite', '[a-z0-9]+'], ['phone_number', '\+[0-9]+'])
                   ->name('get-register-otp-code');

            /**
             * register with invite code
             */
            $router->get('{invite}', 'Auth\RegisterController@getRegisterForm')
                   ->where('invite', '[a-z0-9]+')
                   ->name('registerForm');
        });

        /**
         * reset password
         */
        $router->group(['prefix' => 'password'], function () use ($router) {
            $router->get('reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
                ->name('password.request');
            $router->post('email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
                ->name('password.email');
            $router->get('reset/{token}', 'Auth\ResetPasswordController@showResetForm')
                ->name('password.reset');
            $router->post('reset', 'Auth\ResetPasswordController@reset');
        });
    });

    /**
     * register
     */
    $router->post('users', 'Auth\RegisterController@register')->name('register');
});

//---- Unauthorized users


// Authorized users

$router->group(['middleware' => 'auth:jwt,web'], function () use ($router) {

    $router->get('auth/logout', 'Auth\LoginController@logout')->name('logout');
    $router->get('auth/token', 'Auth\LoginController@tokenRefresh')->name('auth.token.refresh');

    /**
     * User actions
     */
    $router->group(['prefix' => 'profile'], function () use ($router) {
        $router->get('', 'UserController@show')->name('profile');
        $router->put('', 'UserController@update');
        $router->patch('', 'UserController@update')->name('profile.update');
        $router->get('referrals', 'UserController@referrals')->name('referrals');
        $router->post('picture', 'User\PictureController@store')->name('profile.picture.store');
        $router->get('picture.jpg', 'User\PictureController@show')->name('profile.picture.show');
        $router->get('place', 'PlaceController@showOwnerPlace')
            ->name('profile.place.show');
        $router->put('place', 'PlaceController@update');
        $router->patch('place', 'PlaceController@update')
            ->name('profile.place.update');
        $router->get('place/offers', 'PlaceController@showOwnerPlaceOffers')
            ->name('profile.place.offers');
        $router->post('place/picture', 'Place\PictureController@storePicture')->name('place.picture.store');
        $router->post('place/cover', 'Place\PictureController@storeCover')->name('place.cover.store');
    });

    $router->get('users', 'UserController@index')->name('users.index');
    $router->group(['prefix' => 'users/{id}', 'where' => ['id' => '[a-z0-9-]+']], function () use ($router) {
        $router->get('', 'UserController@show')->name('users.show');
        $router->put('/edit', 'UserController@update')->name('users.update');
        $router->patch('/edit', 'UserController@update');
        $router->get('referrals', 'UserController@referrals');
        $router->post('picture', 'User\PictureController@store');
    });

    $router->resource('advert/offers', 'Advert\OfferController', [
        'names'      => [
            'index'  => 'advert.offers.index',
            'show'   => 'advert.offers.show',
            'create' => 'advert.offers.create',
            'store'  => 'advert.offers.store',
            'update'  => 'advert.offers.update'
        ],
        'except'     => [
            'destroy'
        ]
    ]);
    $router->put('advert/offers/{offerId}/status', 'Advert\OfferController@updateStatus')
           ->name('advert.offer.updateStatus');

    $router->group(['prefix' => 'offers/{offerId}'], function () use ($router) {
        $router->post('picture', 'Offer\PictureController@store')->name('offer.picture.store');
        $router->get('activation_code', 'RedemptionController@getActivationCode')->name('redemption.code');
        $router->group(['prefix' => 'redemption'], function () use ($router) {
            $router->get('create', 'RedemptionController@createFromOffer')->name('redemption.create');
            $router->post('', 'RedemptionController@redemption')->name('redemption.store');
            $router->get('{rid}', 'RedemptionController@showFromOffer')->where('rid',
                '[a-z0-9-]+')->name('redemption.show');
        });
    });

    $router->resource('offers', 'User\OfferController', [
        'except' => [
            'create',
            'store',
            'update',
            'destroy'
        ]
    ]);

    $router->resource('redemptions', 'RedemptionController', [
        'except' => [
            'update',
            'destroy'
        ]
    ]);

    $router->get('transactions/create', '\App\Http\Controllers\TransactionController@createTransaction')
           ->name('transactionCreate');
    $router->post('transactions', '\App\Http\Controllers\TransactionController@completeTransaction')
           ->name('transaction.complete');
    $router->get('transactions/{transactionId?}', '\App\Http\Controllers\TransactionController@listTransactions')
           ->where('reansactionId', '[0-9]+')
           ->name('transactionList');

    /**
     * Categories
     */
    $router->get('categories', 'CategoryController@index')
           ->name('categories');
    $router->get('categories/{uuid}', 'CategoryController@show')
           ->name('categories.show');

    /**
     * Places
     */

    $router->get('places/{uuid}/offers', 'PlaceController@showPlaceOffers')
           ->where('uuid', '[a-z0-9-]+')
           ->name('places.offers.show');


    $router->resource('places', 'PlaceController', [
        'except' => [
            'destroy',
            'update'
        ]
    ]);

    /**
     * Activation codes
     */
    $router->get('activation_codes/{code}', 'ActivationCodeController@show')
           ->name('activation_codes.show');

    /**
     * Roles
     */
    $router->get('roles', 'RoleController@index')
           ->name('roles');
    $router->get('roles/{uuid}', 'RoleController@show')
           ->name('roles.show');
});

//---- Authorized users

/**
 * pictures
 */
$router->get('users/{uuid}/picture.jpg', 'User\PictureController@show')->where('uuid',
    '[a-z0-9-]+')->name('users.picture.show');
$router->get('offers/{offerId}/picture.jpg', 'Offer\PictureController@show')->where('offerId',
    '[a-z0-9-]+')->name('offer.picture.show');
$router->get('places/{uuid}/{type}.jpg', 'Place\PictureController@show')->where([
    'uuid',
    '[a-z0-9-]+'
])->name('place.picture.show');

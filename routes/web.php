<?php

/** @var \Illuminate\Routing\Router $router */
$router = app('router');

$router->group(['middleware' => 'investor', 'prefix' => 'service'], function () use ($router) {
    $router->get('nau/{user}', 'Service\NauController@getAccount');
    $router->post('crosschange', 'Service\NauController@exchangeNau');
    $router->post('user/create', 'Service\NauController@createUser');
});
/**
 * register
 */
$router->post('users', 'UserController@store')->name('register');

// Unauthorized users
$router->group(['middleware' => 'guest:jwt,web'], function () use ($router) {

    $router->group(['prefix' => 'auth'], function () use ($router) {

        $router->get('/', function () {
            return redirect()->route('loginForm');
        });

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
});

//---- Unauthorized users


// Authorized users

$router->group(['middleware' => 'auth:jwt,web,operator'], function () use ($router) {

    $router->get('/', function () {
        return response()->render('home', []);
    })->name('home');

    $router->get('auth/logout', 'Auth\LoginController@logout')->name('logout');
    $router->get('auth/token', 'Auth\LoginController@tokenRefresh')->name('auth.token.refresh');

    $router->get('auth/impersonate/{uuid}', 'Auth\LoginController@impersonate')->where('uuid',
        '[a-z0-9-]+')->name('impersonate');
    $router->get('auth/stop_impersonate', 'Auth\LoginController@stopImpersonate')->name('stop_impersonate');

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
        $router->get('place', 'PlaceController@show')
            ->name('profile.place.show');
        $router->get('place/edit', 'PlaceController@edit')
               ->name('profile.place.edit');
        $router->put('place', 'PlaceController@update');
        $router->patch('place', 'PlaceController@update')
            ->name('profile.place.update');
        $router->get('place/offers', 'Advert\OfferController@index')
            ->name('profile.place.offers');
        $router->post('place/picture', 'Place\PictureController@storePicture')->name('place.picture.store');
        $router->post('place/cover', 'Place\PictureController@storeCover')->name('place.cover.store');
    });

    $router->get('users', 'UserController@index')->name('users.index');
    $router->get('users/create', 'UserController@create')->name('users.create');
    $router->group(['prefix' => 'users/{id}', 'where' => ['id' => '[a-z0-9-]+']], function () use ($router) {
        $router->get('', 'UserController@show')->name('users.show');
        $router->put('', 'UserController@update')->name('users.update');
        $router->patch('', 'UserController@update');
        $router->get('referrals', 'UserController@referrals');
        $router->post('picture', 'User\PictureController@store')->name('users.picture.store');
        $router->get('place/create', 'PlaceController@create')->name('users.place.create');
    });

    $router->resource('advert/offers', 'Advert\OfferController', [
        'names'       => [
            'index'   => 'advert.offers.index',
            'show'    => 'advert.offers.show',
            'create'  => 'advert.offers.create',
            'store'   => 'advert.offers.store',
            'destroy' => 'advert.offers.destroy',
            'update'  => 'advert.offers.update',
            'edit'    => 'advert.offers.edit',
        ]
    ]);

    // Advert page for create operator
    $router->resource('advert/operators', 'Advert\OperatorController', [
        'names'       => [
            'index'   => 'advert.operators.index',
            'show'    => 'advert.operators.show',
            'create'  => 'advert.operators.create',
            'store'   => 'advert.operators.store',
            'destroy' => 'advert.operators.destroy',
            'edit'    => 'advert.operators.edit',
            'update'  => 'advert.operators.update',
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
           ->name('transaction.create');
    $router->post('transactions', '\App\Http\Controllers\TransactionController@completeTransaction')
           ->name('transaction.complete');
    $router->get('transactions/{transactionId?}', '\App\Http\Controllers\TransactionController@listTransactions')
           ->name('transaction.list');

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
    $router->post('places/{uuid}/picture', 'Place\PictureController@storePicture')->name('places.picture.store');
    $router->post('places/{uuid}/cover', 'Place\PictureController@storeCover')->name('places.cover.store');


    $router->resource('places', 'PlaceController', [
        'except' => [
            'destroy'
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
])->name('places.picture.show');

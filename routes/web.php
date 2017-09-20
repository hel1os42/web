<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Unauthorized users

Route::group(['middleware' => 'guest'], function () {

    Route::get('/', 'ProfileController@index')->name('home');

    Route::group(['prefix' => 'auth'], function () {
        /**
         * login
         */
        Route::get('login', 'Auth\LoginController@getLogin')
            ->name('loginForm');
        Route::post('login', 'Auth\LoginController@postLogin')
            ->name('login');
        /**
         * register with invite code
         */
        Route::get('register/{invite}', 'Auth\RegisterController@getRegisterForm')
             ->where('invite', '[a-z0-9]+')
             ->name('registerForm');
        /**
         * reset password
         */
        Route::group(['prefix' => 'password'], function () {
            Route::get('reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::get('reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
            Route::post('reset', 'Auth\ResetPasswordController@reset');
        });
    });
    /**
     * register
     */
    Route::post('users', 'Auth\RegisterController@register')->name('register');
});

//---- Unauthorized users


// Authorized users

Route::group(['middleware' => 'auth:jwt-guard,web'], function () {

    Route::get('auth/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('auth/token', 'Auth\LoginController@tokenRefresh')->name('auth.token.refresh');

    Route::group(['prefix' => 'users/{id}', 'where' => ['id' => '[a-z0-9-]+']], function () {
        Route::get('', 'ProfileController@show')->name('users.show');
        Route::get('referrals', 'ProfileController@referrals');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('', 'ProfileController@show')->name('profile');
        Route::get('referrals', 'ProfileController@referrals')->name('referrals');
    });

    Route::resource('advert/offers', 'Advert\OfferController', [
        'names'  => [
            'index'  => 'advert.offers.index',
            'show'   => 'advert.offers.show',
            'create' => 'advert.offers.create',
            'store'  => 'advert.offers.store'
        ],
        'except' => [
            'update',
            'destroy'
        ]
    ]);
    Route::group(['prefix' => 'offers/{offerId}'], function () {
        Route::get('activation_code', 'RedemptionController@getActivationCode')->name('redemption.code');
        Route::group(['prefix' => 'redemption'], function () {
            Route::get('create', 'RedemptionController@create')->name('redemption.create');
            Route::post('', 'RedemptionController@redemption')->name('redemption.store');
            Route::get('{rid}', 'RedemptionController@show')->where('rid',
                '[a-z0-9-]+')->name('redemption.show');
        });
    });

    Route::resource('offers', 'User\OfferController', [
        'except' => [
            'create',
            'store',
            'update',
            'destroy'
        ]
    ]);

    Route::get('transactions/create', '\App\Http\Controllers\TransactionController@createTransaction')
        ->name('transactionCreate');
    Route::post('transactions', '\App\Http\Controllers\TransactionController@completeTransaction')
        ->name('transactionComplete');
    Route::get('transactions/{transactionId?}', '\App\Http\Controllers\TransactionController@listTransactions')
        ->where('reansactionId', '[0-9]+')
        ->name('transactionList');

    Route::get('categories', 'CategoryController@index')->name('categories');
});

//---- Authorized users

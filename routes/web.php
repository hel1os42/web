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

Route::get('/', 'ProfileController@index')->name('home');

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', 'Auth\LoginController@getLogin')->name('loginForm');
    Route::post('login', 'Auth\LoginController@postLogin')->name('login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('register/{invite}', 'Auth\RegisterController@getRegisterForm')
        ->where('invite', '[a-z0-9]+')
        ->name('registerForm');

    Route::group(['prefix' => 'password'], function () {
        Route::get('reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('reset', 'Auth\ResetPasswordController@reset');
    });
});

Route::post('users', 'Auth\RegisterController@register')->name('register');

//---- Unauthorized users


// Authorized users

Route::group(['middleware' => 'auth'], function () {

    Route::get('auth/token', 'Auth\LoginController@tokenRefresh');

    $profile = function () {
        Route::get('', 'ProfileController@show')->name('profile');
        Route::put('', 'ProfileController@update');
        Route::patch('', 'ProfileController@update')->name('profile.update');
        Route::get('referrals', 'ProfileController@referrals')->name('referrals');
        Route::get('photo', 'Profile\PhotoController@show')->name('profile.photo.show');
        Route::post('photo', 'Profile\PhotoController@store')->name('profile.photo.store');
    };

    Route::group(['prefix' => 'users/{id}', 'where' => ['id' => '[a-z0-9-]+']], $profile);
    Route::group(['prefix' => 'profile'], $profile);

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

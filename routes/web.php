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
});

Route::post('users', 'Auth\RegisterController@register')->name('register');

//---- Unauthorized users


// Authorized users

Route::group(['middleware' => 'auth'], function () {

    Route::get('users/{id}', 'ProfileController@show')
        ->where('id', '[a-z0-9-]+')
        ->name('profile');

    Route::resource('advert/offers', 'Advert\OfferController', [
        'names'  => [
            'index'  => 'advert.offer.list',
            'show'   => 'advert.offer.show',
            'create' => 'advert.offerForm',
            'store'  => 'advert.offer'
        ],
        'except' => [
            'update',
            'destroy'
        ]
    ]);

    Route::resource('offers', 'User\OfferController', [
        'except' => [
            'create',
            'store',
            'update',
            'destroy'
        ]
    ]);

    Route::get('category', 'CategoryController@index')->name('category');

    Route::get('transaction/create', '\App\Http\Controllers\TransactionController@createTransaction')->name('transCreate');
    Route::post('transaction/create', '\App\Http\Controllers\TransactionController@completeTransaction')->name('transComplete');

});

//---- Authorized users

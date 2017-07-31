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

Route::get('/', '\App\Http\Controllers\User\ProfileController@index')->name('home');

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', '\App\Http\Controllers\Auth\LoginController@getLogin')->name('loginForm');
    Route::post('login', '\App\Http\Controllers\Auth\LoginController@postLogin')->name('login');
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
    Route::get('register/{invite}', '\App\Http\Controllers\Auth\RegisterController@getRegisterForm')
        ->where('invite', '[a-z0-9]+')
        ->name('registerForm');
});

Route::post('users', '\App\Http\Controllers\Auth\RegisterController@register')->name('register');

//---- Unauthorized users


// Authorized users

Route::group(['middleware' => 'auth'], function () {

    Route::get('users/{id}', '\App\Http\Controllers\User\ProfileController@show')
        ->where('id', '[a-z0-9-]+')
        ->name('profile');
    Route::resource('advert/offers', '\App\Http\Controllers\Advert\OfferController', [
        'names' => [
            'create' => 'advert.offerForm',
            'store'  => 'advert.offer',
            'show'   => 'advert.offer.show',
            'index'  => 'advert.offer.list'
        ]
    ]);
    Route::get('offers', '\App\Http\Controllers\User\SearchOfferController@search')->name('offer.search');
    Route::group(['prefix' => 'offers'], function () {
        Route::get('search', '\App\Http\Controllers\User\SearchOfferController@index')->name('offer.searchForm');

        Route::get('redemption/{id}', '\App\Http\Controllers\User\OfferController@redemption')
            ->where('id', '[a-z0-9-]+')
            ->name('offer.redemption');
        Route::get('category', '\App\Http\Controllers\CategoryController@index')->name('offer.category');

        Route::get('{id}', '\App\Http\Controllers\User\OfferController@show')
            ->where('id', '[a-z0-9-]+')
            ->name('offer.show');
    });

});

//---- Authorized users

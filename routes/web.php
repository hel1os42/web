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

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', '\App\Http\Controllers\Auth\LoginController@getLogin')->name('getLogin');
    Route::post('login', '\App\Http\Controllers\Auth\LoginController@postLogin');
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
});

Route::get('register', '\App\Http\Controllers\Auth\RegisterController@getRegister')->name('getLogin');
Route::post('register', '\App\Http\Controllers\Auth\RegisterController@postRegister');

Route::group(['prefix' => 'user'], function () {
    Route::get('profile', '\App\Http\Controllers\User\ProfileController@show')->name('profile');
});

Route::group(['middleware' => 'jwt.auth'], function () {

});
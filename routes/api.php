<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', '\App\Http\Controllers\Auth\LoginController@login');
    Route::post('logout', '\App\Http\Controllers\Auth\LoginController@logout');
});

Route::group(['middleware' => 'jwt.auth'], function () {

});


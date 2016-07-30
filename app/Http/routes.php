<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('auth', 'Auth\AuthController@getAuth')->name('getAuth');
Route::post('auth', 'Auth\AuthController@postAuth')->name('postAuth');
Route::get('logout', 'Auth\AuthController@logout')->name('logout');

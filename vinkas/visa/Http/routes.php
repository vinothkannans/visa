<?php

Route::group(['namespace' => 'SSO', 'prefix' => 'sso'], function() {

  Route::get('discourse', 'DiscourseController@get')->name('discourse');

});

Route::get('username', 'UsernameController@get')->name('getUsername');
Route::post('username', 'UsernameController@post')->name('postUsername');
Route::post('username/check', 'UsernameController@check')->name('checkUsername');

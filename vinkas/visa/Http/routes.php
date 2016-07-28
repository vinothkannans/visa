<?php

Route::group(['namespace' => 'SSO', 'prefix' => 'sso'], function() {

  Route::get('discourse', 'DiscourseController@get')->name('discourse');

});

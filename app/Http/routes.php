<?php

use \Illuminate\Support\Facades\Redis;

Route::group(['middleware' => 'web'], function () {
    Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

    Route::get('register/confirmation/{confirmation_code}', 'UserController@confirmEmail');

    Route::get('/', function () {
        return view('index');
    });

    Route::resource('/chat', 'ChatController');

    Route::auth();
});

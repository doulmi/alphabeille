<?php

use \Illuminate\Support\Facades\Redis;

Route::group(['middleware' => 'web'], function () {
    Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

    Route::get('register/confirmation/{confirmation_code}', 'UserController@confirmEmail');

    Route::get('/', function () {
        return view('index');
    });

    Route::resource('chat', 'ChatController');
    Route::resource('topics', 'TopicController');
    Route::resource('talkshow', 'TalkshowController');

    Route::auth();
});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api\Controllers'], function($api) {
        $api->get('topics/{id}', 'TopicController@show');
        $api->get('topics', 'TopicController@index');
        $api->get('talkshows/{id}', 'TalkshowController@show');
        $api->get('talkshows', 'TalkshowController@index');
    });
});

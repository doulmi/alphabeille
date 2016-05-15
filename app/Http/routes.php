<?php

use \Illuminate\Support\Facades\Redis;

Route::group(['middleware' => 'web'], function () {
    Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

    Route::get('register/confirmation/{confirmation_code}', 'UserController@confirmEmail');

    Route::get('/', 'PostController@index');

    Route::resource('chat', 'ChatController');
    Route::resource('topics', 'TopicController');
    Route::resource('lessons', 'LessonController');
    Route::resource('talkshows', 'TalkshowController');
    Route::get('menus', 'PostController@menus');

    Route::get('checkEmail', function() {
        return view('emails.checkEmail');
    })->name('checkEmail');

    Route::get('profile/{userId}', 'UserController@profile');
    Route::auth();

//    Auth::loginUsingId(1);

    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {

    });
});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api\Controllers'], function($api) {
        $api->get('topics/{id}', 'TopicController@show');
        $api->get('topics', 'TopicController@index');
        $api->get('talkshows/random', 'TalkshowController@random');
        $api->get('talkshows/{id}', 'TalkshowController@show');
        $api->get('talkshows', 'TalkshowController@index');
    });
});

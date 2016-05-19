<?php

use \Illuminate\Support\Facades\Redis;

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

Route::get('register/confirmation/{confirmation_code}', 'UserController@confirmEmail');

Route::get('/', 'PostController@index');

Route::resource('chat', 'ChatController');
Route::resource('topics', 'TopicController');
Route::resource('lessons', 'LessonController');
Route::resource('talkshows', 'TalkshowController');
Route::resource('messages', 'MessageController');
Route::get('menus', 'PostController@menus');

Route::get('checkEmail', function () {
    return view('emails.checkEmail');
})->name('checkEmail');

Route::get('users/{id}', 'UserController@show');
Route::put('users', 'UserController@update');
Route::post('uploadAvatar', 'UserController@uploadAvatar');
Route::post('modifyPwd', 'UserController@modifyPwd');
Route::auth();

Auth::loginUsingId(51);

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', 'Admin\AdminController@index');
    Route::get('/users', 'Admin\UserController@index')->name('adminUsers');
    Route::put('/users', 'Admin\UserController@store');
    Route::get('/roles', 'Admin\RoleController@index');
    Route::get('/users/changeRole/{userId}/{roleId}', 'Admin\UserController@changeRole');
});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api\Controllers'], function ($api) {
        $api->get('topics/{id}', 'TopicController@show');
        $api->get('topics', 'TopicController@index');
        $api->get('talkshows/random', 'TalkshowController@random');
        $api->get('talkshows/{id}', 'TalkshowController@show');
        $api->get('talkshows', 'TalkshowController@index');
        $api->get('messages/{id}', 'MessageController@show');
    });
});

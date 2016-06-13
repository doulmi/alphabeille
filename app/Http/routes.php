<?php

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

Route::get('register/confirmation/{confirmation_code}', 'UserController@confirmEmail');
//Route::get('flags', function() { return view('flags'); });

Route::get('/', 'PostController@index');

Route::auth();
Route::resource('chat', 'ChatController');
Route::resource('topics', 'TopicController');
Route::post('topics/{ids}/delete', 'TopicController@multiDestroy');
Route::resource('lessons', 'LessonController');
Route::resource('talkshows', 'TalkshowController');
Route::resource('messages', 'MessageController');
Route::resource('discussions', 'DiscussionController');
Route::post('post/upload', 'PostController@upload');
Route::get('menus', 'PostController@menus');

Route::get('checkEmail', function () {
    return view('emails.checkEmail');
})->name('checkEmail');

Route::get('users/{id}', 'UserController@show');
Route::put('users', 'UserController@update');
Route::post('uploadAvatar', 'UserController@uploadAvatar');
Route::post('modifyPwd', 'UserController@modifyPwd');
Route::get('comments/like/{commentId}', 'CommentController@like');
Route::post('comments', 'CommentController@store');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', 'Admin\AdminController@index');
    Route::get('/users', 'Admin\UserController@index')->name('adminUsers');
    Route::put('/users', 'Admin\UserController@store');
    Route::get('/users/changeRole/{userId}/{roleId}', 'Admin\UserController@changeRole');
    Route::get('/roles', 'Admin\RoleController@index');
    Route::put('/roles', 'Admin\RoleController@store');
    Route::get('/topics', 'Admin\TopicController@index');
    Route::put('/topics', 'Admin\TopicController@store');
});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api\Controllers'], function ($api) {
        $api->post('auth/login', 'AuthenticateController@authenticate');
        $api->post('auth/register', 'AuthenticateController@register');
        $api->get('topics/{id}', 'TopicController@show');
        $api->get('topics', 'TopicController@index');
        $api->get('talkshows/random', 'TalkshowController@random');
        $api->get('talkshows', 'TalkshowController@index');
        $api->get('messages/{id}', 'MessageController@show');

        $api->group(['middleware' => 'jwt.auth'], function($api) {
            $api->get('users/me', 'AuthenticateController@getAuthenticatedUser');
        });
    });
});

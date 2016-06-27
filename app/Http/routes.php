<?php

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

Route::get('register/confirmation/{confirmation_code}', 'UserController@confirmEmail');

Route::get('/', 'PostController@index');

Route::auth();
Route::resource('chat', 'ChatController');
Route::resource('topics', 'TopicController');
Route::post('topics/{ids}/delete', 'TopicController@multiDestroy')->where(['id' => '[0-9]+']);
Route::resource('lessons', 'LessonController');
Route::get('lessons/{id}/{lan}', 'LessonController@show');
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
Route::post('lessonComments', 'LessonCommentController@store');
Route::get('lessonComments/{id}', 'LessonCommentController@index');
Route::post('talkshowComments', 'TalkshowCommentController@store');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', 'Admin\AdminController@index');
    Route::get('/users', 'Admin\UserController@index')->name('adminUsers');
    Route::put('/users', 'Admin\UserController@store');
    Route::get('/users/changeRole/{userId}/{roleId}', 'Admin\UserController@changeRole');
    Route::get('/roles', 'Admin\RoleController@index');
    Route::post('/roles', 'Admin\RoleController@store');
    Route::get('/topics', 'Admin\TopicController@index');
    Route::put('/topics', 'Admin\TopicController@store');
    Route::post('/topics/{id}', 'Admin\TopicController@update');
    Route::get('/lessons', 'Admin\LessonController@index');
    Route::post('/lessons', 'Admin\LessonController@store');
    Route::post('/lessons/{topicId}', 'Admin\LessonController@update');
    Route::get('/lessons/{topicId}/create', 'Admin\LessonController@create');
    Route::get('/lessons/{id}/edit', 'Admin\LessonController@edit');

    Route::resource('talkshows', 'Admin\TalkshowController');
//    Route::get('/talkshows', 'Admin\TalkshowController@index');
//    Route::post('/talkshows', 'Admin\TalkshowController@store');
//    Route::put('/talkshows', 'Admin\TalkshowController@update');
//    Route::get('/talkshows/create', 'Admin\TalkshowController@create');
//    Route::get('/talkshows/{id}/edit', 'Admin\TalkshowController@edit');

});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api\Controllers'], function ($api) {
        $api->post('auth/login', 'AuthenticateController@authenticate');
        $api->post('auth/register', 'AuthenticateController@register');
        $api->get('topics/{count}/{page}', 'TopicController@index')->where(['count' => '[0-9]+', 'page' => '[0-9]+']);
        $api->get('topics/{id}', 'TopicController@show')->where(['id' => '[0-9]+']);
        $api->get('talkshows/{count}/{page}', 'TalkshowController@index')->where(['count' => '[0-9]+', 'page' => '[0-9]+']);
        $api->get('topics/{id}/lessons', 'TopicController@lessons')->where(['id' => '[0-9]+']);
        $api->get('lessons/{id}', 'LessonController@show')->where(['id' => '[0-9]+']);
        $api->get('messages/{id}', 'MessageController@show')->where(['id' => '[0-9]+']);
        $api->get('talkshows/{id}', 'TalkshowController@show')->where(['id' => '[0-9]+']);
        $api->group(['middleware' => 'jwt.auth'], function($api) {
            $api->get('users/me', 'AuthenticateController@getAuthenticatedUser');
        });
    });
});

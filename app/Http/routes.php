<?php

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

Route::get('register/confirmation/{confirmation_code}', 'UserController@confirmEmail');

Route::get('/', 'PostController@index');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('chat', 'ChatController');

    Route::get('lessons/collect', 'LessonController@collectLessons');
    Route::post('lessons/{id}/favorite', 'LessonController@favorite');
    Route::post('lessons/{id}/punchin', 'LessonController@punchin');
    Route::post('lessons/{id}/collect', 'LessonController@collect');

    Route::get('talkshows/collect', 'TalkshowController@collectTalkshows');
    Route::post('talkshows/{id}/favorite', 'TalkshowController@favorite');
    Route::post('talkshows/{id}/punchin', 'TalkshowController@punchin');
    Route::post('talkshows/{id}/collect', 'TalkshowController@collect');

    Route::post('minitalks/{id}/punchin', 'MinitalkController@punchin');
    Route::get('minitalks/collect', 'MinitalkController@collectMinitalks');
    Route::post('minitalks/{id}/favorite', 'MinitalkController@favorite');
    Route::post('minitalks/{id}/collect', 'MinitalkController@collect');

    Route::get('users/collect', 'UserController@collect');
//    Route::post('post/upload', 'PostController@upload');
    Route::post('uploadAvatar', 'UserController@uploadAvatar');
    Route::post('modifyPwd', 'UserController@modifyPwd');
    Route::post('comments', 'CommentController@store');
    Route::post('lessonComments', 'LessonController@addComment');
    Route::post('talkshowComments', 'TalkshowController@addComment');
    Route::post('minitalkComments', 'MinitalkController@addComment');
    Route::get('subscription/{id}', 'PostController@subscription');
    Route::get('alipay/pay/{id}', 'AlipayController@pay');
    Route::get('alipay/result', 'AlipayController@result');
});

Route::get('verifyEmail/{confirmation_code}', function($confirmation_code) {
    return view('emails.register', compact('confirmation_code'));
});

Route::get('images', function() {
    return view('images');
});

Route::get('lessonComments/{lesson_id}', 'LessonController@comments');
Route::get('talkshowComments/{talkshow_id}', 'TalkshowController@comments');
Route::get('minitalkComments/{minitalk_id}', 'MinitalkController@comments');


Route::auth();

Route::resource('topics', 'TopicController');
//Route::post('topics/{ids}/delete', 'TopicController@multiDestroy')->where(['id' => '[0-9]+']);
Route::get('lessons/free', 'LessonController@free');
//Route::get('lessons/collect', 'LessonController@myCollect');
Route::get('lessons/{id}/{lan}', 'LessonController@show');
Route::resource('lessons', 'LessonController');

Route::get('talkshows/free', 'TalkshowController@free');
Route::resource('talkshows', 'TalkshowController');

Route::get('videos/free', 'VideoController@free');
Route::resource('videos', 'VideoController');

Route::get('minitalks/free', 'MinitalkController@free');
Route::resource('minitalks', 'MinitalkController');

Route::resource('messages', 'MessageController');
Route::resource('discussions', 'DiscussionController');
Route::get('menus', 'PostController@menus');

Route::get('checkEmail', function () {
    return view('emails.checkEmail');
})->name('checkEmail');

Route::get('users/{id}', 'UserController@show');
Route::get('comments/like/{commentId}', 'CommentController@like');

Route::put('users', 'UserController@update');
Route::get('search', 'PostController@search');
Route::get('free', 'PostController@free');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', 'Admin\AdminController@index');
    Route::get('/users', 'Admin\UserController@index')->name('adminUsers');
    Route::put('/users', 'Admin\UserController@store');
    Route::get('/users/changeRole/{userId}/{roleId}', 'Admin\UserController@changeRole');

    Route::get('/roles', 'Admin\RoleController@index');
    Route::post('/roles', 'Admin\RoleController@store');

    Route::get('/topics', 'Admin\TopicController@index');
    Route::put('/topics', 'Admin\TopicController@store');
    Route::post('/topics/{toppicId}', 'Admin\TopicController@update');
    Route::post('/topics/{id}', 'Admin\TopicController@update');

    Route::get('/lessons', 'Admin\LessonController@index');
    Route::post('/lessons', 'Admin\LessonController@store');
    Route::post('/lessons/{topicId}', 'Admin\LessonController@update');
    Route::get('/lessons/{topicId}/create', 'Admin\LessonController@create');
    Route::get('/lessons/{id}/edit', 'Admin\LessonController@edit');

    Route::get('/video', 'Admin\VideoController@create');

    Route::get('/slugs', 'Admin\AdminController@slugs');
    Route::get('/readables', 'Admin\AdminController@readables');
    Route::get('/comments/{type}', 'Admin\CommentController@index');

    Route::get('/lessonComments/create/{lesson_id}', 'Admin\CommentController@createLessons');
    Route::put('/lessonComments/create/{lesson_id}', 'Admin\CommentController@storeLessons');

    Route::get('/minitalkComments/create/{minitalk_id}', 'Admin\CommentController@createMinitalks');
    Route::put('/minitalkComments/create/{minitalk_id}', 'Admin\CommentController@storeMinitalks');

    Route::get('/talkshowComments/create/{talkshow_id}', 'Admin\CommentController@createTalkshows');
    Route::put('/talkshowComments/create/{talkshow_id}', 'Admin\CommentController@storeTalkshows');

    Route::delete('/comments/{type}/{id}', 'Admin\CommentController@destroy');

    Route::resource('talkshows', 'Admin\TalkshowController');
    Route::resource('minitalks', 'Admin\MinitalkController');

    Route::get('/addUsers', 'Admin\AdminController@addUsers');
    Route::get('/changeDate', 'Admin\AdminController@changeDate');
    Route::get('/updateViews/{day}', 'Admin\AdminController@updateViews');
    Route::get('/saveParsedContent', 'Admin\AdminController@saveParsedContent');
    Route::get('/transferComment', 'Admin\AdminController@transferComment');
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
        $api->group(['middleware' => 'jwt.auth'], function ($api) {
            $api->get('users/me', 'AuthenticateController@getAuthenticatedUser');
        });
    });
});

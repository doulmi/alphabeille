<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'confirmation_code' => str_random(65),
        'avatar' => $faker->imageUrl(256, 256),
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Topic::class, function (Faker\Generator $faker) {

    $levels = ['beginner', 'intermediate', 'advanced'];
    return [
        'title' => $faker->text(50),
        'description' => $faker->text(200),
        'avatar' => $faker->imageUrl(256, 256),
        'level' => $faker->randomElement($levels),
        'created_at' => \Carbon\Carbon::now()
    ];
});

$lesson_order = 1;
$factory->define(App\Lesson::class, function (Faker\Generator $faker) use (&$lesson_order) {
    $topicIds = \App\Topic::lists('id')->toArray();
    return [
        'title' => $faker->text(50),
        'description' => $faker->text(200),
        'order' => $lesson_order ++,
        'avatar' => $faker->imageUrl(200, 153),
        'views' => $faker->numberBetween(0, 300),
        'audio_url_zh_CN' => $faker->url,
        'audio_url' => $faker->url,
        'download_url' => $faker->url,
        'content' => $faker->realText(1000),
        'duration' => $faker->numberBetween(0, 60) . ":" . $faker->numberBetween(0,60),
        'likes' => $faker->numberBetween(0, 300),
        'free' => $faker->boolean,
        'topic_id' => $faker->randomElement($topicIds),
        'created_at' => \Carbon\Carbon::now()
    ];
});

//$table->integer('user_id')->unsigned();
//$table->integer('subscription_id')->unsigned();
//$table->timestamp('expire_at');
//$table->integer('price');
$factory->define(App\UserSubscription::class, function (Faker\Generator $faker) {
    $userIds = \App\User::lists('id')->toArray();
    $subscriptionIds = \App\Subscription::lists('id')->toArray();
    return [
        'user_id' => $faker->randomElement($userIds),
        'subscription_id' => $faker->randomElement($subscriptionIds),
        'expire_at' => $faker->dateTime,
        'price' => $faker->numberBetween(400, 2000),
    ];
});

//$table->text('content');
//$table->integer('user_id')->unsigned();
$factory->define(App\Discussion::class, function (Faker\Generator $faker) {
    $userIds = \App\User::lists('id')->toArray();
    return [
        'user_id' => $faker->randomElement($userIds),
        'content' => $faker->text,
        'last_answer_by' => $faker->randomElement($userIds),
        'created_at' => \Carbon\Carbon::now()
    ];
});


/**
$table->integer('article_id')->unsigned();
$table->integer('user_id')->unsigned();
$table->text('content');
 */
$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    $userIds = \App\User::lists('id')->toArray();
    $discussionleIds = \App\Discussion::lists('id')->toArray();

    return [
        'user_id' => $faker->randomElement($userIds),
        'content' => $faker->text,
        'discussion_id' => $faker->randomElement($discussionleIds),
        'created_at' => \Carbon\Carbon::now()
    ];
});

/**
$table->integer('article_id')->unsigned();
$table->integer('user_id')->unsigned();
$table->text('content');
 */
$factory->define(App\LessonComment::class, function (Faker\Generator $faker) {
    $userIds = \App\User::lists('id')->toArray();
    $lessonIds = \App\Lesson::lists('id')->toArray();

    return [
        'user_id' => $faker->randomElement($userIds),
        'content' => $faker->text,
        'lesson_id' => $faker->randomElement($lessonIds),
        'created_at' => \Carbon\Carbon::now()
    ];
});

$factory->define(App\Talkshow::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->text(10),
        'description' => $faker->text(50),
        'avatar' => $faker->imageUrl(256, 256),
        'free' => $faker->boolean,
        'audio_url' => $faker->url,
        'audio_url_zh_CN' => $faker->url,
        'download_url' => $faker->url,
        'content' => $faker->realText(1000),
        'duration' => $faker->numberBetween(0, 60) . ":" . $faker->numberBetween(0,60),
        'likes' => $faker->numberBetween(0, 200),
        'views' => $faker->numberBetween(0, 200),
        'created_at' => \Carbon\Carbon::now()
    ];
});

$factory->define(App\Message::class, function (Faker\Generator $faker) {
    $userIds = \App\User::lists('id')->toArray();
    return [
        'to' => $faker->randomElement($userIds),
        'from' => $faker->randomElement($userIds),
        'title' => $faker->text(50),
        'isRead' => $faker->boolean,
        'content' => $faker->text(300),
    ];
});
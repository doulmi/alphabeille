<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        factory(\App\User::class, 50)->create();
//
        factory(\App\Topic::class, 100)->create();
        factory(\App\Lesson::class, 800)->create();
        factory(\App\Talkshow::class, 400)->create();

        \App\Subscription::create([ 'name' => '6month', 'duration' => '6', 'price' => '520', 'description' => '6monthDesc' ]);
        \App\Subscription::create([ 'name' => '12month', 'duration' => '12', 'price' => '960', 'description' => '12monthDesc' ]);
        \App\Subscription::create([ 'name' => '24month', 'duration' => '23', 'price' => '1800', 'description' => '24monthDesc' ]);

        factory(\App\UserSubscription::class, 400)->create();
        factory(\App\LessonComment::class, 1000)->create();
        factory(\App\Article::class, 100)->create();
        factory(\App\Comment::class, 200)->create();
    }
}

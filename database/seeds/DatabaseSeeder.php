<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Model::unguard();

        factory(\App\User::class, 50)->create();
        $this->call(RoleSeeder::class);
        $this->call(RoleUserSeeder::class);

        factory(\App\Topic::class, 100)->create();
        factory(\App\Lesson::class, 800)->create();
        factory(\App\Talkshow::class, 400)->create();

        $this->call(SubscriptionSeeder::class);

        factory(\App\UserSubscription::class, 400)->create();
        factory(\App\LessonComment::class, 1000)->create();
        factory(\App\Discussion::class, 100)->create();
        factory(\App\Comment::class, 200)->create();

        $this->call(MessageSeeder::class);

        $this->call(PermissionSeeder::class);
        Model::reguard();
    }
}

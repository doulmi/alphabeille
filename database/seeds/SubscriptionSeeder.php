<?php

use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Subscription::create([ 'name' => '6month', 'duration' => '6', 'price' => '520', 'description' => 'readAll' ]);
        \App\Subscription::create([ 'name' => '12month', 'duration' => '12', 'price' => '960', 'description' => 'readAll|freeGroup1' ]);
        \App\Subscription::create([ 'name' => '12month+', 'duration' => '24', 'price' => '1800', 'description' => 'readAll|group12' ]);
    }
}

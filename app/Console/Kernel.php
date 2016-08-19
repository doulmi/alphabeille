<?php

namespace App\Console;

use App\Lesson;
use App\Minitalk;
use App\Video;
use Faker\Factory;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
         Commands\AutoVideos::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
//            $this->addViews();
            $faker = Factory::create();

            $videos = Video::all();
            foreach ($videos as $video) {
                Redis::set('video:view:' . $video->id, Redis::get('video:view:' . $video->id) + $faker->randomNumber(2));
            }

            $minitalks = Minitalk::all();
            foreach ($minitalks as $minitalk) {
                Redis::set('minitalk:view:' . $minitalk->id, Redis::get('minitalk:view:' . $minitalk->id) + $faker->randomNumber(2));
            }

            $lessons = Lesson::all();
            foreach ($lessons as $lesson) {
                Redis::set('lesson:view:' . $lesson->id, Redis::get('lesson:view:' . $lesson->id) + $faker->randomNumber(2));
            }
//            $this->saveViews();
            //save views
            $videos = Video::all();
            foreach ($videos as $video) {
                $video->views = Redis::get('video:view:' . $video->id);
                $video->save();
            }
            Log::info('schedule works');
        })->daily();

        $schedule->call(function (){
//            $this->saveLastMonthViews();
            //save views
            $videos = Video::all();
            foreach ($videos as $video) {
                //save translators money
                //0. get all translators
                //1. foreach($translators) get all his videos published less than 3 months
                //2. get (current Month views - last month views) * ratio, and count
                $video->lastMonthViews = Redis::get('video:view:' . $video->id);
                $video->save();
            }
        })->monthly();
    }

    private function addViews() {

    }

    private function saveLastMonthViews()
    {

    }

    private function saveViews()
    {

    }
}


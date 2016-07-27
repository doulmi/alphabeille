<?php

namespace App\Http\Controllers\Admin;

use App\Lesson;
use App\Minitalk;
use App\Readable;
use App\Talkshow;
use App\Topic;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Http\Request;
use Faker\Factory;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function changeDate() {
        $users = User::all();

        $faker = Factory::create();
        foreach ($users as $user) {
            if($user->id >= 9) {
                $user->created_at = $faker->dateTimeBetween('-21 days', 'now');
                $user->save();
            }
        }
    }

    public function readables() {
        $entity = Lesson::find(1);
        $readable = Readable::create([
            'title' => $entity->title,
            'description' => $entity->description,
            'avatar' => $entity->avatar,
            'free' => $entity->free,
            'audio_url' => $entity->audio_url,
            'download_url' => $entity->audio_url,
            'duration' => $entity->duration,
            'content' => $entity->content,
            'keywords' => $entity->keywords,
            'is_published' => $entity->is_published,
            'publish_at' => $entity->publish_at,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ]);
//        $lessons = Lesson::all();
//        foreach($lessons as $lesson) {
//            Readable::create($lesson);
//        }
//
//        $topics = Topic::all();
//        foreach($topics as $topic) {
//            $topic->save();
//        }
//
//        $minitalks = Minitalk::all();
//        foreach($minitalks as $minitalk) {
//            $minitalk->save();
//        }
//
//        $talkshows = Talkshow::all();
//        foreach($talkshows as $talkshow) {
//            $talkshow->save();
//        }
    }

    public function slugs() {
        $lessons = Lesson::all();
        foreach($lessons as $lesson) {
            $lesson->save();
        }

        $topics = Topic::all();
        foreach($topics as $topic) {
            $topic->save();
        }

        $minitalks = Minitalk::all();
        foreach($minitalks as $minitalk) {
            $minitalk->save();
        }

        $talkshows = Talkshow::all();
        foreach($talkshows as $talkshow) {
            $talkshow->save();
        }
    }
}

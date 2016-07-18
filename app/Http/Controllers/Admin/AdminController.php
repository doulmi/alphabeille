<?php

namespace App\Http\Controllers\Admin;

use App\Lesson;
use App\Minitalk;
use App\Talkshow;
use App\Topic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
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

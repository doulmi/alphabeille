<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\Http\Controllers\TalkshowController;
use App\Http\Controllers\TopicController;
use App\Lesson;
use App\Subscription;
use App\Talkshow;
use Illuminate\Http\Request;
use EndaEditor;

use App\Http\Requests;

class PostController extends Controller
{
    public function index()
    {
        $lessons = Lesson::latest()->limit(8)->get();
        $talkshows = Talkshow::latest()->limit(8)->get();

        $menus = Subscription::all();
        foreach($menus as $menu) {
            $menu->advantages = explode('|', $menu->description);
        }
        return view('indexWithLessons', compact(['lessons', 'talkshows', 'menus']));
    }

    public function menus()
    {
        $menus = Subscription::all();
        return view('menus', compact('menus'));
    }

    public function upload()
    {
        $data = EndaEditor::uploadImgFile('uploads');
        return json_encode($data);
    }
}

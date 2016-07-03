<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\Editor\Markdown\Markdown;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\TalkshowController;
use App\Http\Controllers\TopicController;
use App\Lesson;
use App\Subscription;
use App\Talkshow;
use Illuminate\Http\Request;
use EndaEditor;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use TomLingham\Searchy\Facades\Searchy;

class PostController extends Controller
{
    public function index()
    {
        $lessons = Lesson::latest()->limit(8)->get();
        $talkshows = Talkshow::latest()->limit(8)->get();

        $menus = Subscription::all();
        foreach ($menus as $menu) {
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

    public function search()
    {
        $keys = Input::get('keys', '');
        $talkshows = Searchy::talkshows(['title', 'content', 'content_zh_CN'])->query($keys)->get();
        $lessons = Searchy::lessons(['title', 'content', 'content_zh_CN'])->query($keys)->get();
        $discussions = Searchy::discussions(['title', 'content'])->query($keys)->get();
        return view('search', compact(['talkshows', 'lessons', 'discussions']));
    }

    public function free() {
        $talkshows = Talkshow::where('free', 1)->get();
        $lessons = Lesson::where('free', 1)->get();
        return view('free', compact(['talkshows', 'lessons']));
    }

    public function subscription($id) {
        $menu = Subscription::findOrFail($id);
        return view('payment', compact('menu'));
    }
}

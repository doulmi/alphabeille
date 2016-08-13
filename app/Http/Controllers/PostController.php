<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Lesson;
use App\Minitalk;
use App\Subscription;
use App\Talkshow;

use App\Http\Requests;
use App\Video;
use App\Word;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use TomLingham\Searchy\Facades\Searchy;

class PostController extends Controller
{
    public function index()
    {
        $num = Config::get('params')['indexPageLimit'];
        $lessons = Lesson::published()->orderBy('free', 'DESC')->orderBy('id', 'DESC')->limit($num)->get(['id', 'avatar', 'title', 'slug', 'created_at']);

//        $talkshows = Talkshow::published()->orderBy('free', 'DESC')->latest()->limit($num)->get(['id', 'avatar', 'title', 'slug', 'created_at','level']);

        $minitalks = Minitalk::published()->orderBy('free', 'DESC')->latest()->limit($num)->get(['id', 'avatar', 'title', 'slug', 'created_at']);

        $videos = Video::published()->orderBy('free', 'DESC')->latest()->limit($num)->get(['id', 'avatar', 'title', 'slug', 'created_at','level']);
        return view('index', compact(['lessons', 'talkshows', 'minitalks', 'videos']));
    }

    public function menus()
    {
        $menus = Subscription::all();
        return view('menus', compact('menus'));
    }

//    public function upload()
//    {
//        $data = EndaEditor::uploadImgFile('uploads');
//        return json_encode($data);
//    }

    public function search()
    {
        $keys = Input::get('keys', '');
        $talkshows = Searchy::talkshows(['title', 'content', 'content_zh_CN'])->query($keys)->get();
        $lessons = Searchy::lessons(['title', 'content', 'content_zh_CN'])->query($keys)->get();
        $discussions = Searchy::discussions(['title', 'content'])->query($keys)->get();
        $minitalks = Searchy::minitalks(['title', 'content'])->query($keys)->get();
        return view('search', compact(['talkshows', 'lessons', 'discussions', 'minitalks']));
    }

    public function free()
    {
        $talkshows = Talkshow::where('free', 1)->get();
        $lessons = Lesson::where('free', 1)->get();
        $minitalks = Minitalk::where('free', 1)->get();
        return view('free', compact(['talkshows', 'lessons', 'minitalks']));
    }

    public function subscription($id)
    {
        $menu = Subscription::findOrFail($id);
        return view('payment', compact('menu'));
    }

    public function basicCourses()
    {
        return view('cours/basicCourses');
    }

    public function oralFormation()
    {
        return view('cours/oralFormation');
    }

    public function privateCourses()
    {
        return view('cours/privateCourses');
    }
}

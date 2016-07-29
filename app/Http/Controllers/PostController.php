<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Minitalk;
use App\Subscription;
use App\Talkshow;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use TomLingham\Searchy\Facades\Searchy;

class PostController extends Controller
{
    public function index()
    {
        $num = Config::get('params')['indexPageLimit'];
        $lessons = Lesson::orderBy('id', 'DESC')->limit($num)->get();

        $talkshows = Talkshow::latest()->limit($num)->get();
        $minitalks = Minitalk::latest()->limit($num)->get();

//        $menus = Subscription::all();
//        foreach ($menus as $menu) {
//            $menu->advantages = explode('|', $menu->description);
//        }
        return view('index', compact(['lessons', 'talkshows', 'minitalks']));
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

}

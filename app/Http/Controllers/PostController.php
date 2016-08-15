<?php

namespace App\Http\Controllers;

use App\Minitalk;
use App\Subscription;

use App\Http\Requests;
use App\Video;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use TomLingham\Searchy\Facades\Searchy;

class PostController extends Controller
{
    public function index()
    {
        $num = Config::get('params')['indexPageLimit'];
        $minitalks = Minitalk::published()->orderBy('free', 'DESC')->latest()->limit($num)->get(['id', 'avatar', 'title', 'slug', 'created_at']);

        $videos = Video::published()->orderBy('free', 'DESC')->latest()->limit($num)->get(['id', 'avatar', 'title', 'slug', 'created_at','level']);
        return view('index', compact(['minitalks', 'videos']));
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
        $minitalks = Searchy::minitalks(['title', 'content', 'wechat_part'])->query($keys)->get();
        $videos = Searchy::videos(['title', 'content', 'description'])->query($keys)->get();
        return view('search', compact(['minitalks', 'videos']));
    }

    public function free()
    {
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

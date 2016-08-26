<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Minitalk;
use App\Subscription;

use App\Http\Requests;
use App\Video;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Sunra\PhpSimple\HtmlDomParser;
use TomLingham\Searchy\Facades\Searchy;

class PostController extends Controller
{
    public function test() {
//        $root = HtmlDomParser::file_get_html("https://www.youtube.com/watch?v=dyrq5gq9Iao");
//        $title = $root->


//        $title = $root->find('title');
//        echo $title->plaintext;

//        $descr = $root->find('meta[description]');
//        echo $descr->plaintext;
//        $title = $root->find("title", 1);
//        dd($title->innertext);
    }

    public function index()
    {
        $num = Config::get('params')['indexPageLimit'];
        $minitalks = Minitalk::published()->orderBy('free', 'DESC')->latest()->limit($num)->get(['id', 'avatar', 'title', 'slug', 'created_at', 'free']);

        $videos = Video::published()->orderBy('free', 'DESC')->latest()->limit($num)->get(['id', 'avatar', 'title', 'slug', 'created_at','level', 'free']);
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
//        $minitalks = Minitalk::where('title', 'like', "%$keys%")->orWhere('content', 'like', "%$keys%")->orWhere('wechat_part', 'like', "%$keys%")->get(['']);
        $videos = Video::where('title', 'like', '%' . $keys . '%')->orWhere('content', 'like', '%' . $keys .'%')->orWhere('description', 'like', '%' . $keys . '%')->paginate(50, ['views', 'id', 'title', 'avatar', 'slug']);
        return view('search', compact('videos'));
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

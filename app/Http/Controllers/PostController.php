<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Minitalk;
use App\Subscription;

use App\Http\Requests;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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

        $videos = Video::published()->orderBy('free', 'DESC')->orderBy('state', 'DESC')->latest()->limit($num)->get(['id', 'avatar', 'title', 'slug', 'created_at','level', 'free', 'state']);
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

    public function search(Request $request)
    {
        $keys = $request->get('keys', '');
        $page = $request->has('page') ? $request->page - 1 : 0;
        $limit = 48;
        $videos = Video::search($keys)->get();
        //Paginate bug in 5.2
        $total = $videos->count();
        $videos = $videos->slice($page * $limit, $limit);
        $videos = new \Illuminate\Pagination\LengthAwarePaginator($videos, $total, $limit);
        $videos->setPath('/search')->appends(['q' => $keys]);
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

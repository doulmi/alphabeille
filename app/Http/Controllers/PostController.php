<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Minitalk;
use App\SearchHistory;
use App\Subscription;

use App\Http\Requests;
use App\Video;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Sunra\PhpSimple\HtmlDomParser;
use TomLingham\Searchy\Facades\Searchy;

class PostController extends Controller
{


    public function index()
    {
        $num = Config::get('params')['indexPageLimit'];
        $minitalks = Minitalk::orderBy('free', 'DESC')->latest()->limit($num)->get(['id', 'avatar', 'title', 'slug', 'created_at', 'free', 'views', 'duration']);

        $videoCols = ['id', 'avatar', 'title', 'slug', 'created_at','level', 'free', 'state', 'views', 'duration'];

        $videos[] = Video::orderBy('free', 'DESC')->latest()->limit($num)->get($videoCols);
        $videos[] = Video::orderBy('free', 'DESC')->orderBy('views', 'DESC')->limit($num)->get($videoCols);

        return view('index', compact('minitalks', 'videos', 'recommendVideo'));
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
        //record
        $user = Auth::user();
        if($user) {
            //user is login
            $userId = $user->id;
        } else {
            $userId = 1;    //super admin id
        }

        SearchHistory::create([
            'user_id' => $userId,
            'search_text' => $request->get('keys')
        ]);

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

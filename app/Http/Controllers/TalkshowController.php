<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\Talkshow;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redis;

class TalkshowController extends Controller
{
    private $pageLimit = 24;

    //Redis中的次数累计到viewMax，写入到数据库中
    private $viewMax = 100;

    private $markdown;
    /**
     * @param $makrdown
     */
    public function __construct(Markdown $markdown)
    {
        $this->markdown = $markdown;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $talkshows = Talkshow::latest()->paginate($this->pageLimit);
        return view('talkshows.index', compact('talkshows'));
    }

    public function latest($num)
    {
        $talkshows = Talkshow::latest()->limit($num)->get();
        return $talkshows;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function random($num = 4, $max = 100)
    {
        $talkshows = Talkshow::latest()->limit($max)->get();
        return $talkshows->random($num);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $talkshow = Talkshow::findOrFail($id);

        Redis::incr('talkshow:view:' . $id);
        Redis::incr('talkshows:viewAll:' . $id);

        $views = Redis::get('talkshow:view:' . $id);

        //每100次访问，才更新一次数据库
        //TODO : 全部改成Redis
        if ($views == $this->viewMax) {
            $talkshow->views += $this->viewMax;
            $talkshow->save();
        }

        $talkshows = $this->random();
        $next = Talkshow::where('id', '>', $id)->orderBy('id')->limit(1)->first(['id']);
        $pre = Talkshow::where('id', '<', $id)->orderBy('id', 'desc')->limit(1)->first(['id']);
        $comments = $talkshow->comments;
        $content = $this->markdown->parse($talkshow->content);

        return view('talkshows.show', compact(['talkshow', 'talkshows', 'comments', 'next', 'pre', 'content']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

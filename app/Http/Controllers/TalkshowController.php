<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\Talkshow;
use App\TalkshowCollect;
use App\TalkshowFavorite;
use App\UserPunchin;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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
        $num = $num > $talkshows->count() ? : $num;
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

        $talkshows = $this->random();
//        $next = Talkshow::where('id', '>', $id)->orderBy('id')->limit(1)->first(['id']);
//        $pre = Talkshow::where('id', '<', $id)->orderBy('id', 'desc')->limit(1)->first(['id']);
        $comments = $talkshow->comments;
        $content = $this->markdown->parse($talkshow->content);

        $like = false;
        $collect = false;
        $punchin = false;

        if (!Auth::guest()) {
            $model = TalkshowFavorite::where('user_id', Auth::user()->id)->where('talkshow_id', $id)->first();
            if ($model) {
                $like = true;
            }
            $model = TalkshowCollect::where('user_id', Auth::user()->id)->where('talkshow_id', $id)->first();
            if ($model) {
                $collect = true;
            }
            $model = UserPunchin::where('user_id', Auth::user()->id)->whereDate('created_at', '=', Carbon::today()->toDateString())->first();
            if ($model) {
                $punchin = true;
            }
        }

        $readable = $talkshow;
        $type = 'talkshow';
        return view('talkshows.show', compact(['readable', 'type', 'talkshows', 'comments', 'content', 'like', 'collect', 'punchin']));
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

    public function free() {
        $talkshows = Talkshow::where('free', 1)->latest()->paginate($this->pageLimit);
        return view('talkshows.index', compact('talkshows'));
    }

    public function favorite($id)
    {
        return $this->doAction($id, TalkshowFavorite::class);
    }

    public function collectTalkshows()
    {
        $talkshows = TalkshowCollect::where('user_id', Auth::user()->id)->paginate($this->pageLimit);
        return view('talkshows.index', compact('talkshows'));
    }

    public function punchin($id)
    {
        $user = Auth::user();
        $punchin = UserPunchin::where('user_id', $user->id)->whereDate('created_at', '=', Carbon::today()->toDateString())->first();
        if (!$punchin) {

            UserPunchin::create([
                'punchable_type' => 'App\Talkshow',
                'punchable_id' => $id,
                'user_id' => $user->id
            ]);

            $break = false;
            $user->series++;
            $shouldUpdateMaxSeries = $user->series > $user->maxSeries;
            if ($shouldUpdateMaxSeries) {
                $user->maxSeries = $user->series;
                $break = true;
            }
            $user->save();
            return response()->json([
                'status' => 200,
                'break' => $break,
                'series' => $user->series
            ]);
        }
    }

    private function doAction($id, $class)
    {
        //不登录没权限
        if (Auth::guest()) {
            return response()->json([
                'status' => 403,
            ]);
        }

        $model = $class::where([
            'talkshow_id' => $id,
            'user_id' => Auth::user()->id
        ])->first();

        //已经收藏或喜欢的话会取消
        if ($model) {
            $model->delete();

            return response()->json([
                'status' => 200
            ]);
        } else {
            $class::create([
                'talkshow_id' => $id,
                'user_id' => Auth::user()->id
            ]);

            return response()->json([
                'status' => 200
            ]);
        }
    }

    public function collect($id)
    {
        return $this->doAction($id, TalkshowCollect::class);
    }
}

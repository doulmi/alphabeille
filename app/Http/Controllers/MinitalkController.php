<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\Minitalk;
use App\MinitalkCollect;
use App\MinitalkFavorite;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class MinitalkController extends Controller
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
        $minitalks = Minitalk::latest()->paginate($this->pageLimit);
        return view('minitalks.index', compact('minitalks'));
    }

    public function latest($num)
    {
        $minitalks = Minitalk::latest()->limit($num)->get();
        return $minitalks;
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

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $minitalk = Minitalk::findOrFail($id);

        Redis::incr('minitalk:view:' . $id);

        $next = Minitalk::where('id', '>', $id)->orderBy('id')->limit(1)->first(['id']);
        $pre = Minitalk::where('id', '<', $id)->orderBy('id', 'desc')->limit(1)->first(['id']);
        $comments = $minitalk->comments;
        $content = $this->markdown->parse($minitalk->content);
        $wechat_part = $this->markdown->parse($minitalk->wechat_part);

        $like = false;
        $collect = false;

        if (!Auth::guest()) {
            $model = MinitalkFavorite::where('user_id', Auth::user()->id)->where('minitalk_id', $id)->first();
            if ($model) {
                $like = true;
            }
            $model = MinitalkCollect::where('user_id', Auth::user()->id)->where('minitalk_id', $id)->first();
            if ($model) {
                $collect = true;
            }
        }
        return view('minitalks.show', compact(['minitalk', 'comments', 'next', 'pre', 'content', 'like', 'collect', 'wechat_part']));
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
        $minitalks = Minitalk::where('free', 1)->latest()->paginate($this->pageLimit);
        return view('$minitalks.index', compact('minitalks'));
    }

    public function favorite($id)
    {
        return $this->doAction($id, MinitalkFavorite::class);
    }

    public function collectMinitalks()
    {
        $minitalks = MinitalkCollect::where('user_id', Auth::user()->id)->paginate($this->pageLimit);
        return view('minitalks.index', compact('minitalks'));
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
            'minitalk_id' => $id,
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
                'minitalk_id' => $id,
                'user_id' => Auth::user()->id
            ]);

            return response()->json([
                'status' => 200
            ]);
        }
    }

    public function collect($id)
    {
        return $this->doAction($id, MinitalkCollect::class);
    }
}

<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\Minitalk;
use App\MinitalkCollect;
use App\MinitalkFavorite;
use App\UserPunchin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    public function show($idOrSlug)
    {
//        $minitalk = Minitalk::findOrFail($id);
        $minitalk = Minitalk::findByIdOrSlugOrFail($idOrSlug);

        $id = $minitalk->id;
        Redis::incr('minitalk:view:' . $id);

//        $next = Minitalk::where('id', '>', $id)->orderBy('id')->limit(1)->first(['id']);
//        $pre = Minitalk::where('id', '<', $id)->orderBy('id', 'desc')->limit(1)->first(['id']);
        $comments = $minitalk->comments;
//        $content = $this->markdown->parse($minitalk->content);
//        $wechat_part = $this->markdown->parse($minitalk->wechat_part);
        $content = $minitalk->parsed_content;
        $wechat_part = $minitalk->parsed_wechat_part;

        $like = false;
        $collect = false;
        $punchin = false;

//        'title' => $entity->title,
//            'description' => $entity->description,
//            'avatar' => $entity->avatar,
//            'free' => $entity->free,
//            'audio_url' => $entity->audio_url,
//            'download_url' => $entity->audio_url,
//            'duration' => $entity->duration,
//            'content' => $entity->content,
//            'keywords' => $entity->keywords,
//            'is_published' => $entity->is_published,
//            'publish_at' => $entity->publish_at,
//            'created_at' => $entity->created_at,
//            'updated_at' => $entity->updated_at
//
//        $minitalk->title = $

        if (!Auth::guest()) {
            $model = MinitalkFavorite::where('user_id', Auth::user()->id)->where('minitalk_id', $id)->first();
            if ($model) {
                $like = true;
            }
            $model = MinitalkCollect::where('user_id', Auth::user()->id)->where('minitalk_id', $id)->first();
            if ($model) {
                $collect = true;
            }

            $model = UserPunchin::where('user_id', Auth::user()->id)->whereDate('created_at', '=', Carbon::today()->toDateString())->first();
            if ($model) {
                $punchin = true;
            }
        }

        $readable = $minitalk;
        $type = 'minitalk';
        return view('minitalks.show', compact(['readable', 'type', 'comments', 'content', 'like', 'collect', 'punchin', 'wechat_part']));
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

    public function punchin($id)
    {
        $user = Auth::user();
        if(!$user) {
            return response()->json([
                'status' => 403,
            ]);
        }
        $punchin = UserPunchin::where('user_id', $user->id)->whereDate('created_at', '=', Carbon::today()->toDateString())->first();
        if (!$punchin) {
            UserPunchin::create([
                'punchable_type' => 'App\Lesson',
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
}

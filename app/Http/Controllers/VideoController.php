<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\UserTraces;
use App\Video;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class VideoController extends ReadableController
{
    private $markdown;

    /**
     * @param $makrdown
     */
    public function __construct(Markdown $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $idOrSlug)
    {
        $readable = Video::findByIdOrSlugOrFail($idOrSlug);

        Redis::incr('video:view:' . $readable->id);

        $readables = $this->random();
        $user = Auth::user();

        if ($user) {
            UserTraces::create([
                'user_id' => $user->id,
                'readable_type' => 'App\Video',
                'readable_id' => $readable->id
            ]);
        }

        $fr = explode('||', $readable->parsed_content);
        $zh = explode('||', $readable->parsed_content_zh);

        list($like, $collect, $punchin) = $this->getStatus($readable);

        $type = 'video';
        $youtube = $user->last_login_foreign;
        return view('videos.show', compact(['readables', 'type', 'readable', 'fr', 'zh', 'like', 'collect', 'punchin', 'youtube']));
    }

    public function level($level)
    {
        $pageLimit = config('params')['pageLimit'];
        $readables = Video::where('level', $level)->published()->orderBy('free', 'DESC')->orderBy('state', 'DESC')->latest()->paginate($pageLimit, ['id', 'avatar', 'title', 'slug', 'created_at','level', 'free', 'state']);
        $type = 'video';
        return view('videos.index', compact(['readables', 'type']));
    }

    public function index(Request $request) {
        $pageLimit = config('params')['pageLimit'];
        $cols = ['id', 'avatar', 'title', 'slug', 'created_at','level', 'free', 'state'];
        $readables = Video::published()->orderBy('free', 'DESC')->orderBy('state', 'DESC')->latest()->paginate($pageLimit, $cols)->appends($request->all());
        $type = 'video';
        return view('videos.index', compact(['readables', 'type']));
    }
}

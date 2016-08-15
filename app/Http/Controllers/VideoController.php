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

        if($user) {
            UserTraces::create([
                'user_id' => $user->id,
                'readable_type' => 'App\Video',
                'readable_id' => $readable->id
            ]);
        }

        $fr = explode('||', $readable->parsed_content);
        $zh = explode('||', $readable->parsed_content_zh);

        list($like, $collect, $punchin) = $this->getStatus($readable);
//        $readable->desc = $this->markdown->parse($readable->description);

        $type = 'video';
//        $location = Location::get($request->ip());
//        $youtube = true;
        $youtube = false;
//        if ($location->countryCode == 'ZH') {
            //中国，使用本地
//            $youtube = false;
//        }
        return view('videos.show', compact(['readables', 'type', 'readable', 'fr', 'zh', 'like', 'collect', 'punchin', 'youtube']));
    }

    public function level($level)
    {
        $pageLimit = config('params')['pageLimit'];
        $readables = Video::where('level', $level)->published()->latest()->orderBy('free')->paginate($pageLimit, ['id', 'avatar', 'title', 'slug', 'created_at', 'level']);
        $type = 'video';
        return view('videos.index', compact(['readables', 'type']));
    }
}

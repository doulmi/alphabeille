<?php

namespace App\Http\Controllers;

use App\UserPunchin;
use App\Video;
use App\VideoCollect;
use App\VideoFavorite;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;

class VideoController extends ReadableController
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idOrSlug)
    {
        $readable = Video::findByIdOrSlugOrFail($idOrSlug);

        Redis::incr('video:view:' . $readable->id);

        $readables = $this->random();
//        $next = Talkshow::where('id', '>', $id)->orderBy('id')->limit(1)->first(['id']);
//        $pre = Talkshow::where('id', '<', $id)->orderBy('id', 'desc')->limit(1)->first(['id']);
        $fr = explode('||', $readable->parsed_content);
        $zh = explode('||', $readable->parsed_content_zh);

        list($like, $collect, $punchin) = $this->getStatus($readable);

        $type = 'video';
        return view('videos.show', compact(['readables', 'type', 'readable', 'fr', 'zh', 'like', 'collect', 'punchin']));
    }
}

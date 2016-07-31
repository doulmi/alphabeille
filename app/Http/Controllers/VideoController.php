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
        $video = Video::findByIdOrSlugOrFail($idOrSlug);

        Redis::incr('video:view:' . $video->id);

        $videos = $this->random();
//        $next = Talkshow::where('id', '>', $id)->orderBy('id')->limit(1)->first(['id']);
//        $pre = Talkshow::where('id', '<', $id)->orderBy('id', 'desc')->limit(1)->first(['id']);
        $fr = explode('||', $video->parsed_content);
        $zh = explode('||', $video->parsed_content_zh);

        list($like, $collect, $punchin) = $this->getStatus($video);

        $readables = $videos;
        $type = 'video';
        return view('videos.show', compact(['readables', 'type', 'video', 'fr', 'zh', 'like', 'collect', 'punchin']));
    }
}

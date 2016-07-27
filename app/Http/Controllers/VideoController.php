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
use Illuminate\Support\Facades\Redis;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idOrSlug)
    {
        $video = Video::findByIdOrSlugOrFail($idOrSlug);

        Redis::incr('talkshow:view:' . $video->id);

        $comments = $video->comments;
        $content = $video->parsed_content;

        $like = false;
        $collect = false;
        $punchin = false;

        if (!Auth::guest()) {
            $model = VideoFavorite::where('user_id', Auth::user()->id)->where('video_id', $id)->first();
            if ($model) {
                $like = true;
            }
            $model = VideoCollect::where('user_id', Auth::user()->id)->where('video_id', $id)->first();
            if ($model) {
                $collect = true;
            }
            $model = UserPunchin::where('user_id', Auth::user()->id)->whereDate('created_at', '=', Carbon::today()->toDateString())->first();
            if ($model) {
                $punchin = true;
            }
        }

        $readable = $video;
        $type = 'video';
        return view('videos.show', compact(['readable', 'type', 'comments', 'content', 'like', 'collect', 'punchin']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

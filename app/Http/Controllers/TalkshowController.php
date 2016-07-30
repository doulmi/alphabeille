<?php

namespace App\Http\Controllers;

use App\Talkshow;
use App\Http\Requests;
use Illuminate\Support\Facades\Redis;

class TalkshowController extends ReadableController
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($idOrSlug)
    {
        $talkshow = Talkshow::findByIdOrSlugOrFail($idOrSlug);

        Redis::incr('talkshow:view:' . $talkshow->id);

        $talkshows = $this->random();
//        $next = Talkshow::where('id', '>', $id)->orderBy('id')->limit(1)->first(['id']);
//        $pre = Talkshow::where('id', '<', $id)->orderBy('id', 'desc')->limit(1)->first(['id']);
        $content = $talkshow->parsed_content;

        list($like, $collect, $punchin) = $this->getStatus($talkshow);

        $readable = $talkshow;
        $type = 'talkshow';
        return view('talkshows.show', compact(['readable', 'type', 'talkshows', 'content', 'like', 'collect', 'punchin']));
    }
}

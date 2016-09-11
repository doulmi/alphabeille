<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Minitalk;
use App\UserTraces;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;

class MinitalkController extends ReadableController
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($idOrSlug)
    {
        $readable = Minitalk::findByIdOrSlugOrFail($idOrSlug);

//        Redis::incr('minitalk:view:' . $readable->id);

        $readable->increment('views');
//        $comments = $readable->comments;
        $content = $readable->parsed_content;
        $wechat_part = $readable->parsed_wechat_part;

        $user = Auth::user();
        if($user) {
            UserTraces::create([
                'user_id' => $user->id,
                'readable_type' => 'App\Minitalk',
                'readable_id' => $readable->id
            ]);
            $user->update([
                'mins' => Helper::str2Min($readable->duration) + $user->mins
            ]);
        }

        list($like, $collect, $punchin) = $this->getStatus($readable);

        $type = 'minitalk';
        return view('minitalks.show', compact(['readable', 'type', 'content', 'like', 'collect', 'punchin', 'wechat_part']));
    }

}

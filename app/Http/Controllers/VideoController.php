<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\Video;

use App\Http\Requests;
use Illuminate\Support\Facades\Redis;

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idOrSlug)
    {
        $readable = Video::findByIdOrSlugOrFail($idOrSlug);

//        Redis::incr('video:view:' . $readable->id);

        $readables = $this->random();
        $fr = explode('||', $readable->parsed_content);
        $zh = explode('||', $readable->parsed_content_zh);

        list($like, $collect, $punchin) = $this->getStatus($readable);
        $readable->desc = $this->markdown->parse($readable->description);

        $type = 'video';
        return view('videos.show', compact(['readables', 'type', 'readable', 'fr', 'zh', 'like', 'collect', 'punchin']));
    }
}

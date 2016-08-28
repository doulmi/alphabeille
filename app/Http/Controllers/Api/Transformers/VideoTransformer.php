<?php

namespace App\Http\Controllers\Api\Transformers;

use App\Talkshow;
use App\Video;
use League\Fractal\TransformerAbstract;

class VideoTransformer extends TransformerAbstract
{
    public function transform(Video $video) {
        return [
            'id' => $video->id,
            'title' => $video->title,
            'likes' => $video->likes,
            'views' => $video->views,
            'free' => $video->free,
            'avatar' => $video->avatar,
            'duration' => $video->duration,
            'youtubeId' => $video->originSrc
        ];
    }
}
<?php

namespace App\Http\Controllers\Api\Transformers;

use App\Talkshow;
use League\Fractal\TransformerAbstract;

class TalkshowTransformer extends TransformerAbstract
{
    public function transform(Talkshow $talkshow) {
        return [
            'id' => $talkshow->id,
            'title' => $talkshow->title,
            'description' => $talkshow->description,
            'likes' => $talkshow->likes,
            'views' => $talkshow->views,
            'free' => $talkshow->free,
            'avatar' => $talkshow->avatar,
            'duration' => $talkshow->duration,
            'created_at' => $talkshow->create_at
        ];
    }
}
<?php

namespace App\Http\Controllers\Api\Transformers;

use App\Talkshow;
use App\Topic;
use League\Fractal\TransformerAbstract;

class TopicTransformer extends TransformerAbstract
{
    public function transform(Topic $topic) {
        return [
            'title' => $topic->title,
            'description' => $topic->description,
            'likes' => $topic->likes(),
            'views' => $topic->views(),
            'lessons' => $topic->lessonCount(),
            'avatar' => $topic->avatar
        ];
    }
}
<?php

namespace App\Http\Controllers\Api\Transformers;

use App\Topic;
use League\Fractal\TransformerAbstract;

class TopicTransformer extends TransformerAbstract
{
    public function transform(Topic $topic) {
        return [
            'id' => $topic->id,
            'title' => $topic->title,
            'description' => $topic->description,
            'likes' => $topic->likes(),
            'level' => $topic->level,
            'views' => $topic->views(),
            'lessons' => $topic->lessonCount(),
            'avatar' => $topic->avatar
        ];
    }
}
<?php

namespace App\Http\Controllers\Api\Transformers;

use App\Lesson;
use App\Talkshow;
use League\Fractal\TransformerAbstract;

class LessonTransformer extends TransformerAbstract
{
    public function transform(Lesson $lesson) {
        return [
            'id' => $lesson->id,
            'title' => $lesson->title,
            'description' => $lesson->description,
            'likes' => $lesson->likes,
            'views' => $lesson->views,
            'avatar' => $lesson->avatar,
            'duration' => $lesson->duration,
            'created_at' => $lesson->create_at
        ];
    }
}
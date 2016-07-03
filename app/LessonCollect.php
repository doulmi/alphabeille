<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonCollect extends Model
{
    protected $fillable = [
        'lesson_id', 'user_id',
    ];
}

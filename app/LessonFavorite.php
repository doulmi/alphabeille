<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonFavorite extends Model
{
    protected $fillable = [
        'lesson_id', 'user_id',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonComment extends Model
{
    protected $fillable = [
        'lesson_id', 'content', 'user_id', 'created_at'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

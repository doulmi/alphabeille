<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'title', 'description', 'avatar',
    ];

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    public function views() {
        return Lesson::where('topic_id', $this->id)->sum('views');
    }

    public function likes() {
        return Lesson::where('topic_id', $this->id)->sum('likes');
    }

    public function lessonCount() {
        return Lesson::where('topic_id', $this->id)->count();
    }
}

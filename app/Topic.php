<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Topic extends Model
{
    protected $fillable = [
        'title', 'description', 'avatar', 'level'
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

    public function isUpdated() {
        $lesson = Lesson::where('topic_id', $this->id)->latest()->limit(1)->first();
        dd($lesson);
        return (Carbon::now()->diffInDays($lesson->created_at)) < Config::get('topic_updated_days');
    }

    public function isNew() {
        $lesson = Lesson::where('topic_id', $this->id)->orderBy('created_at', 'desc')->limit(1)->first();
        return (Carbon::now()->diffInDays($lesson->created_at)) < Config::get('topic_updated_days');
    }
}

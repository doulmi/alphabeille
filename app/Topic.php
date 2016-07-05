<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Topic extends Model
{
    protected $fillable = [
        'title', 'description', 'avatar', 'level', 'keywords'
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

    public function getState() {
        $lesson = Lesson::where('topic_id', $this->id)->latest()->first();
        if($lesson != null) {
            $isUpdated = (Carbon::now()->diffInDays($lesson->created_at)) < Config::get('topic_updated_days');
            $isNew = (Carbon::now()->diffInDays($lesson->created_at)) < Config::get('topic_updated_days');
            return [
                'isUpdated' => $isUpdated,
                'isNew' => $isNew
            ];
        } else {
            return [
                'isUpdated' => false,
                'isNew' => false,
            ];
        }
    }
}

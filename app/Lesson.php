<?php

namespace App;

use Carbon\Carbon;
use Config;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'topic_id', 'title', 'order', 'views', 'likes', 'description', 'free', 'audio_url', 'download_url', 'duration',  'audio_url_zh_CN', 'content'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function isNew()
    {
        return (Carbon::now()->diffInDays($this->created_at)) < Config::get('topic_updated_days');
    }

    public function comments() {
        return $this->hasMany(LessonComment::class);
    }
}

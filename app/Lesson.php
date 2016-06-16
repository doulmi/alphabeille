<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'topic_id', 'title', 'order', 'views', 'likes', 'description', 'free', 'audio_url', 'download_url', 'duration'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}

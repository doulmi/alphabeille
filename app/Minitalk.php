<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Minitalk extends Model
{
    protected $dates = ['publish_at'];
    protected $fillable = [
        'title', 'description', 'avatar', 'likes', 'views', 'avatar', 'audio_url', 'download_url', 'keywords', 'is_published', 'publish_at', 'free', 'wechat_part', 'content'
    ];

    public function isNew()
    {
        return (Carbon::now()->diffInDays($this->created_at)) < Config::get('topic_updated_days');
    }

    public function comments() {
        return $this->hasMany(MinitalkComment::class);
    }
}

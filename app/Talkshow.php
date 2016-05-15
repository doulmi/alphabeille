<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Talkshow extends Model
{
    protected $fillable = [
        'title', 'description', 'avatar', 'likes', 'views', 'avatar', 'free'
    ];

    public function isNew() {
//        $lesson = Talkshow::orderBy('created_at', 'desc')->limit(1)->first();
        return (Carbon::now()->diffInDays($this->created_at)) < Config::get('topic_updated_days');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Readable extends Model
{
    protected $dates = ['publish_at'];

    protected $fillable = [
        'title', 'description', 'avatar', 'likes', 'views', 'free', 'audio_url', 'download_url', 'duration', 'content',  'keywords', 'is_published', 'publish_at', 'created_at', 'updated_at'
    ];
}

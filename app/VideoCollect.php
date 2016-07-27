<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoCollect extends Model
{
    protected $fillable = [
        'video_id', 'user_id',
    ];
}

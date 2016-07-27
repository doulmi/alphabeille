<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoComment extends Model
{
    protected $fillable = [
        'video_id', 'user_id', 'content', 'created_at'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

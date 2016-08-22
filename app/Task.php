<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['user_id', 'video_id', 'content', 'is_submit', 'type'];

    public function video() {
        return $this->hasOne(Video::class, 'id', 'video_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

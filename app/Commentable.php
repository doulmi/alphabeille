<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commentable extends Model
{
    protected $fillable = [
        'commentable_id', 'commentable_type', 'user_id', 'content'
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

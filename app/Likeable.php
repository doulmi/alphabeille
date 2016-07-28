<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Likeable extends Model
{
    protected $fillable = [
        'likeable_id', 'likeable_type', 'user_id', 'content'
    ];

    public function likeable()
    {
        return $this->morphTo();
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

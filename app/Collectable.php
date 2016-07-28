<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collectable extends Model
{
    protected $fillable = [
        'collectable_id', 'collectable_type', 'user_id', 'content'
    ];

    public function collectable()
    {
        return $this->morphTo();
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

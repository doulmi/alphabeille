<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [ 'point', 'content', 'readable_id', 'readable_type', 'user_id'];

    public function note()
    {
        return $this->morphTo();
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

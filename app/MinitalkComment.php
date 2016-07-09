<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MinitalkComment extends Model
{
    protected $fillable = [
        'minitalk_id', 'user_id', 'content',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

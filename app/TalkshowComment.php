<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TalkshowComment extends Model
{
    protected $fillable = [
        'talkshow_id', 'content', 'user_id',
    ];
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

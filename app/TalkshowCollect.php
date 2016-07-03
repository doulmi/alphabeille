<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TalkshowCollect extends Model
{
    protected $fillable = [
        'talkshow_id', 'user_id',
    ];
}

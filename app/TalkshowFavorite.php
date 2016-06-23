<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TalkshowFavorite extends Model
{
    protected $fillable = [
        'talkshow_id', 'user_id',
    ];
}

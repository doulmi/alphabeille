<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talkshow extends Model
{
    protected $fillable = [
        'title', 'description', 'avatar', 'likes', 'views', 'avatar'
    ];
}

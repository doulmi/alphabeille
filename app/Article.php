<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    protected $fillable = ['id', 'reciteAt', 'type', 'content', 'url', 'limitTime', 'parsedContent', 'hash'];
}

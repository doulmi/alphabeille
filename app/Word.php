<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $fillable = ['id', 'word', 'explication', 'frequency', 'audio'];
}

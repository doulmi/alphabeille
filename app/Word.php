<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $fillable = ['id', 'word', 'explication'];

    public function collects()
    {
        return $this->morphMany('App\Collectable', 'collectable');
    }
}

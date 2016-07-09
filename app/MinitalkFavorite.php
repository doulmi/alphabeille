<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MinitalkFavorite extends Model
{
    protected $fillable = [
        'minitalk_id', 'user_id',
    ];
}

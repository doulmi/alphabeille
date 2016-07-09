<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MinitalkCollect extends Model
{
    protected $fillable = [
        'minitalk_id', 'user_id',
    ];
}

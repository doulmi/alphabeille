<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubError extends Model
{
    protected $fillable = ['user_id', 'video_id', 'line', 'subtitle', 'translate'];
}

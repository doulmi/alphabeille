<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPunchin extends Model
{
    protected $fillable = [
        'punchable_id', 'user_id', 'punchable_type'
    ];
}

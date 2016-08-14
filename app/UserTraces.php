<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTraces extends Model
{
    protected $fillable = ['id', 'user_id','readable_type', 'readable_id'];

    public function user() {
        return $this->hasOne(User::class);
    }
}

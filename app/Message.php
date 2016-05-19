<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['title', 'content', 'from', 'to', 'isRead' ];

    public function from() {
        return User::where('id', $this->from)->first();
    }

    public function to() {
        return User::where('id', $this->to)->first();
    }
}

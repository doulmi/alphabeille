<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $dates = ['fixtop_expire_at'];
    protected $fillable = [
        'content', 'user_id', 'last_answer_by', 'title'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lastAnswerBy() {
        return $this->belongsTo(User::class, 'last_answer_by');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}

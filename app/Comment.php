<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'discussion_id', 'content', 'user_id'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes() {
        return $this->hasMany(UserCommentLike::class, 'comment_id');
    }
}

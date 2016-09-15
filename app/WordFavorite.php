<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordFavorite extends Model
{
    protected $fillable = ['id', 'word_id', 'user_id', 'readable_type', 'readable_id', 'times'];

    public function word() {
        return $this->hasOne(Word::class, 'id', 'word_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
  protected $fillable = [
    'id', 'date', 'content', 'parsedContent', 'hash',
  ];

  protected $dates = [
    'date'
  ];
}

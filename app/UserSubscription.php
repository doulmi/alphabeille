<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $dates = ['expire_at'];
    protected $fillable= [
        'user_id', 'subscription_id', 'extends', 'price', 'expire_at'
    ];
}

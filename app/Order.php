<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['billing_token', 'subscription_id', 'subject', 'order_number', 'type', 'user_id'];
}

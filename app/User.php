<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'wechat', 'QQ', 'isAdmin', 'confirmed', 'confirmation_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

//    public function roles() {
//        return $this->belongsToMany(Role::class);
//    }

//    public function hasRole($role) {
//        if( is_string($role) ) {
//            return $this->roles->contains('name', $role);
//        } else {
//            return !! $role->intersect($this->roles)->count();
//        }
//        //$user->attach($role)
//        //$user->detach($role)
//        //
//    }
}

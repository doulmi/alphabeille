<?php

namespace App;

use Bican\Roles\Models\Role;
use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasRoleAndPermissionContract {
    use HasRoleAndPermission;

    protected $dates = ['last_login_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'wechat', 'QQ', 'confirmed', 'confirmation_code', 'series', 'maxSeries', 'remember_token', 'qq_id', 'wechat_id', 'facebook_id', 'birthYear', 'location', 'sex', 'hasEmail', 'last_login_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function messages() {
        return (Message::where('to', $this->id)->orderBy('isRead')->get());
    }

    public function sendedMessages() {
        return $this->hasMany(Message::class, 'from');
    }

    public function unreadMeesages() {
        return Message::where('to', $this->id)->where('isRead', false);
    }

    public function unreadMessageCount() {
        return Message::where('to', $this->id)->where('isRead', false)->count();
    }

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

<?php

namespace App;

use Auth;
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
        'name', 'email', 'password', 'avatar', 'wechat', 'QQ', 'confirmed', 'confirmation_code', 'series', 'maxSeries', 'remember_token', 'qq_id', 'wechat_id', 'facebook_id', 'birthYear', 'location', 'sex', 'hasEmail', 'last_login_at', 'mins'
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


    public function learnedVideos() {
        $traces = UserTraces::where('user_id', $this->id)->where('readable_type', 'App\Video')->distinct()->get(['readable_id'])->toArray();
        return Video::whereIn('id', $traces);
    }

    public function learnedMinitalks() {
        $traces = UserTraces::where('user_id', $this->id)->where('readable_type', 'App\Minitalk')->distinct()->get(['readable_id'])->toArray();
        return Minitalk::whereIn('id', $traces);
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

    public function translatedVideos() {
        return $this->hasMany(Video::class, 'id', 'video_id');
    }

    public function translatedNumber() {
        return Task::where('type', 2)->where('is_submit', 1)->where('user_id', $this->id)->count();
    }

    public function checkFrNumber() {
        return Task::where('type', 1)->where('is_submit', 1)->where('user_id', $this->id)->count();
    }

    public function mins() {
        $mins = $this->mins;

        if($mins >= 1000) {
            return round($mins / 60, 2) . trans('labels.hour');
        } else {
            return $mins . trans('labels.minute');
        }
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

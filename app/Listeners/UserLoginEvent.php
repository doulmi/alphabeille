<?php

namespace App\Listeners;

use App\Events\UserLogin;
use App\Message;
use App\UserSubscription;
use Bican\Roles\Models\Role;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserLoginEvent
{
    private $expireNotificationDate = 7;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLogin $event
     * @return void
     */
    public function handle(UserLogin $event) {
        $user = Auth::user();
        $user->last_login_at = Carbon::now();

        //检查用户订阅是否已经过期
        if($user->level() == 2) {
            $subscripition = UserSubscription::where('user_id', $user->id)->latest()->limit(1)->first(['expire_at']);
            //需要提醒用户
            if($subscripition->expire_at->addDays($this->expireNotificationDate)->lt(Carbon::now())) {
                //发送Notification给用户
//                Message::create()
                Message::create([
                    'from' => '1',
                    'to' => $user->id,
                    'title' => trans('labels.expire_title', ['date' => $subscripition->expire_at]),
                    'content' => trans('labels.expire_content', [
                        'date' => $subscripition->expire_at
                    ])
                ]);
            }
            if ($subscripition->expire_at->lt(Carbon::now())) {

                $role = Role::findOrFail(3);
                //过期了
                $user->detachAllRoles();
                $user->attachRole($role);
            }
        }
        $user->save();
    }
}

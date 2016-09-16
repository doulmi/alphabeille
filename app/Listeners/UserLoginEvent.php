<?php

namespace App\Listeners;

use App\Events\UserLogin;
use App\Message;
use App\UserPunchin;
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
    private $expireNotificationDate = 0;

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

        $this->checkPunchin($user);
        $this->checkSubscriptionExpired($user);

        $user->save();
    }

    private function checkPunchin(&$user) {
        //检查用户的打卡是否需要归0
        $lastPunchin = UserPunchin::where('user_id', $user->id)->orderBy('created_at', 'DESC')->first();
        if ($lastPunchin && Carbon::today()->diffInDays($lastPunchin->created_at) > 0) {
            $user->series = 0;
        }
    }

    private function checkSubscriptionExpired(&$user) {
        //检查用户订阅是否已经过期
        if($user->isVIP()) {
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
                //过期了
                $user->detachAllRoles();
            }
        }
    }
}

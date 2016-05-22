<?php

namespace App\Listeners;

use App\Events\UserLogin;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserLoginEvent
{
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
    public function handle(UserLogin $event)
    {
        $user = Auth::user();
        $user->last_login_at = Carbon::now();
        $user->save();
    }
}

<?php

namespace App\Listeners;

use App\Events\At;
use App\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserBeAt
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
     * @param  At  $event
     * @return void
     */
    public function handle(At $event)
    {
        $user = $event->destUser;
        $discussion = $event->discussion;
        Message::create([
            'from' => '1',
            'to' => $user->id,
            'title' => trans('labels.haveNewReply', [
                'discussName' => $discussion->title,
                'discussId' => $discussion->id,
                'userName' => $user->name,
                'userId' => $user->id
            ])
        ]);
    }
}

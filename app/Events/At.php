<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class At extends Event
{
    use SerializesModels;

    public $destUser;
    public $discussion;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $discussion)
    {
        $this->destUser = $user;
        $this->discussion = $discussion;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}

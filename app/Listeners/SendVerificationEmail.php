<?php

namespace App\Listeners;

use App\Events\UserRegister;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail
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
     * @param  UserRegister  $event
     * @return void
     */
    public function handle(UserRegister $event)
    {
        $user = $event->user;
        $data = [
            'confirmation_code' => $user->confirmation_code,
        ];

        $subject = 'Confirmation your email';

        Mail::queue('emails.register', $data, function($message) use ($user, $subject) {
            $message->to($user->email)->subject($subject);
        });
    }
}

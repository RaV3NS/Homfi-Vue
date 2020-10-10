<?php

namespace App\Listeners;

use App\Events\EmailChanged;
use App\Events\UserActive;

class SendEmailUserActiveNotification
{
    /**
     * Handle the event.
     *
     * @param EmailChanged $event
     * @return void
     */
    public function handle(UserActive $event)
    {
        if ($event->user->hasVerifiedEmail()) {
            $event->user->sendEmailUserActiveNotification();
        }
    }
}

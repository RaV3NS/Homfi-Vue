<?php

namespace App\Listeners;

use App\Events\EmailChanged;
use App\Events\UserBlocked;

class SendEmailUserBlockedNotification
{
    /**
     * Handle the event.
     *
     * @param EmailChanged $event
     * @return void
     */
    public function handle(UserBlocked $event)
    {
        if ($event->user->hasVerifiedEmail()) {
            $event->user->sendEmailUserBlockedNotification();
        }
    }
}

<?php

namespace App\Listeners;

use App\Events\EmailChanged;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SendEmailChangedVerificationNotification
{
    /**
     * Handle the event.
     *
     * @param EmailChanged $event
     * @return void
     */
    public function handle(EmailChanged $event)
    {
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $event->user->sendEmailChangedVerificationNotification();
        }
    }
}

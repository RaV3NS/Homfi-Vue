<?php

namespace App\Mails;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class UserBlockedEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function __construct($locale = 'uk')
    {
        $this->locale = $locale;
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $event = $notifiable->history()->latest()->first();

        return (new MailMessage)
            ->subject(trans('reasons.admin_block_user', [], $this->locale))
            ->line(trans('reasons.admin_block_user_all_adverts', [], $this->locale))
            ->line("Причина: " . $event->title)
            ->line($event->body);
    }
}

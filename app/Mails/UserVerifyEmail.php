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

class UserVerifyEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    protected $verificationUrl;

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
        $this->verificationUrl = $this->getVerificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->verificationUrl);
        }

        return (new MailMessage)
            ->subject(Lang::get('auth.verify_email'))
            ->line(Lang::get('auth.click_button'))
            ->action(Lang::get('auth.verify_email'), $this->verificationUrl)
            ->line(Lang::get('auth.no_further_action'));
    }

    protected function getVerificationUrl($notifiable)
    {
        $url = env('APP_FRONT_URL') .
            URL::signedRoute(
            'verification-email-link',
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ],
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            false
        );

        return $url;
    }
}

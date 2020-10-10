<?php

namespace App\Providers;

use App\Events\EmailChanged;
use App\Events\UserActive;
use App\Events\UserBlocked;
use App\Listeners\SendEmailChangedVerificationNotification;
use App\Listeners\SendEmailUserActiveNotification;
use App\Listeners\SendEmailUserBlockedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        EmailChanged::class => [
            SendEmailChangedVerificationNotification::class,
        ],
        UserBlocked::class => [
            SendEmailUserBlockedNotification::class,
        ],
        UserActive::class => [
            SendEmailUserActiveNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function notifications()
    {
        return $this->hasMany(AdminNotification::class);
    }

    public function notifies()
    {
        return AdminNotification::query()->where('status', '=', AdminNotification::STATUS_NEW)->latest()->limit(5)->get();
        //$this->hasMany(AdminNotification::class, 'admin_id')->where('status', '=', AdminNotification::STATUS_NEW);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    const STATUS_SOLVED = 'solved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PENDING = 'pending';

    public static $statuses = [
        self::STATUS_SOLVED,
        self::STATUS_REJECTED,
        self::STATUS_PENDING
    ];

    protected $guarded = [];

    public function history()
    {
        return $this->hasMany(ComplainLog::class)->latest();
    }

    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

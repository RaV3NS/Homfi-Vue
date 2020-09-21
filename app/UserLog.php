<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $guarded = [];

    protected $with = [
        'author'
    ];

    public function author()
    {
        return $this->belongsTo(AdminUser::class, 'admin_id');
    }
}

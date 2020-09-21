<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Administrative extends Model
{
    use SoftDeletes;

    protected $table = 'administrative';

    protected $fillable = [
        'name_ru',
        'name_uk',
        'old_name_ru',
        'old_name_uk',
        'osm_id',
        'city_id',
        'translit'
    ];

    public static function getFieldName($name = 'name') {
        return $name . '_' . app()->getLocale();
    }

    public function getNameAttribute()
    {
        return $this->{'name_' . app()->getLocale()};
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function activeAdverts()
    {
        return $this->hasMany(Advert::class)->where('status', Advert::STATUS_ENABLED);
    }
}

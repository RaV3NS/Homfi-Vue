<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subway extends Model
{
    protected $fillable = [
        'name',
        'name_ru',
        'name_uk',
        'old_name',
        'old_name_ru',
        'old_name_uk',
        'lat',
        'lon',
        'city_id',
        'osm_id',
        'translit'
    ];

    public static function getFieldName($field = 'name_uk')
    {
        return $field;
    }

    public function getNameAttribute()
    {
        return $this->{'name_' . app()->getLocale()};
    }

    public function activeAdverts()
    {
        return $this->hasMany(Advert::class)->where('status', Advert::STATUS_ENABLED);
    }


}

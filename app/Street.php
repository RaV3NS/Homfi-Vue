<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Street extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'prefix',
        'prefix_ru',
        'name_uk',
        'name_ru',
        'uuid',
        'city_id',
        'translit'
    ];

    public static function getFieldName($field = 'name_uk')
    {
        if(app()->getLocale() !== config('app.locale')) {
            $field .= '_' . app()->getLocale();
        }

        return $field;
    }

    public function getNameAttribute($name)
    {
        if(app()->getLocale() == 'ru'){
            return $this->name_ru;
        }

        return $this->name_uk;
    }

    public function getNameRuAttribute($name)
    {
        $parts = [$name];
        if(!empty($this->prefix_ru)) {
            array_unshift($parts, $this->prefix_ru);
        }

        return join(' ', $parts);
    }

    public function getNameUkAttribute($name)
    {
        $parts = [$name];
        if(!empty($this->prefix)) {
            array_unshift($parts, $this->prefix);
        }

        return join(' ', $parts);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function activeAdverts()
    {
        return $this->hasMany(Advert::class)->where('status', Advert::STATUS_ENABLED);
    }

    public static function getNormalizePrefix($prefix)
    {
        switch ($prefix) {
            case 'пров.':
                $prefix = 'провулок';
                break;

            case 'пл.':
                $prefix = 'площа';
                break;

            case 'вул.':
                $prefix = 'вулиця';
                break;

            case 'Просп.':
                $prefix = 'проспект';
                break;

            case 'мікр.':
                $prefix = 'мікрорайон';
                break;

            case 'бул.':
                $prefix = 'бульвар';
                break;

            case 'кв.':
                $prefix = 'квартал';
                break;

            case 'наб.':
                $prefix = 'набережна';
                break;
        }

        return $prefix;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**  @OA\Schema(
 *      schema="City",
 *      @OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="uuid",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="translit",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="name_uk",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="name_ru",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="region",
 *          ref="#/components/schemas/Region"
 *      )
 * )
 * @OA\Schema(
 *      schema="SearchGeoObject",
 *      @OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="uuid",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="translit",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="name_uk",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="name_ru",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="city_id",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="adverts_count",
 *          type="integer"
 *      )
 * )
 */

/**
 * Class City
 * @package App
 */

class City extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name_uk',
        'name_ru',
        'uuid',
        'region_id',
        'translit',
        'lat',
        'lng',
        'rank'
    ];

    public static $geo_types = [
        'administrative',
        'street',
        'subway'
    ];

    public static function getFieldName($field = 'name_uk')
    {
        return $field;
    }

    public function getNameAttribute()
    {
        return $this->{'name_' . app()->getLocale()};
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function streets()
    {
        return $this->hasMany(Street::class, 'city_id', 'id');
    }

    public function adverts()
    {
        return $this->hasMany(Advert::class);
    }

    public function activeAdverts()
    {
        return $this->hasMany(Advert::class)->where('status', Advert::STATUS_ENABLED);
    }

    public function getFullCityNameUkAttribute()
    {
        $regionName = $this->region->name_uk;
        $names = preg_split('/\s/', $regionName);
        if (substr($names[0], -4) == 'ий') {
            $regionName .= ' район';
        } else {
            $regionName = 'район '.$regionName;
        }
        return $this->name_uk.', '.$regionName.', '.$this->region->district->name_uk.' область';
    }

    public function getFullCityNameRuAttribute()
    {
        $regionName = $this->region->name_ru;
        $names = preg_split('/\s/', $regionName);
        if (substr($names[0], -4) == 'ий') {
            $regionName .= ' район';
        } else {
            $regionName = 'район '.$regionName;
        }
        return $this->name_ru.', '.$regionName.', '.$this->region->district->name_ru.' область';
    }

    public function getOSMCoordinates()
    {

    }
}

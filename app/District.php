<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**  @OA\Schema(
 *      schema="District",
 *      @OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="uuid",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="name_uk",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="name_ru",
 *          type="string"
 *      )
 * )
 * */

class District extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'uuid',
        'name_uk',
        'name_ru',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function regions()
    {
        return $this->hasMany(Region::class, 'district_id', 'id');
    }

    public function getNameAttribute()
    {
        return $this->{'name_' . app()->getLocale()};
    }
}

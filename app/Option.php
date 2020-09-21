<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**  @OA\Schema(
 *      schema="Option",
 *      @OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string"
 *      )
 *   )
 */
class Option extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = ['pivot'];

    public function getNameAttribute($value)
    {
        return trans('options.' . $value);
    }
}

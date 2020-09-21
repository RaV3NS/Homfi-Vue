<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{

/**  @OA\Schema(
 *      schema="Favorite",
 *      @OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="advert",
 *          ref="#/components/schemas/Advert"
 *      ),
 *  )
 */

    public $timestamps = false;
    protected $guarded = [];

    protected $with = ['advert'];

    public function advert()
    {
        return $this->belongsTo(Advert::class, 'advert_id', 'id')->with(['parameters', 'options']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

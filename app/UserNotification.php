<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**  @OA\Schema(
 *      schema="Notification",
 *      @OA\Property(
 *          property="id",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="owner",
 *          type="object",
 *          ref="#/components/schemas/User"
 *      ),
 *      @OA\Property(
 *          property="type",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="title",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="body",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="datetime"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          format="datetime"
 *      )
 *  )
 */

class UserNotification extends Model
{
    protected $guarded = [];

    protected $with = ['owner'];


    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function advert()
    {
        return $this->belongsTo(Advert::class, 'advert_id')->without('user');
    }

    public function getTitleAttribute($value)
    {
        $result = new \StdClass;
        $result->uk = trans('reasons.' . $value, [], 'uk');
        $result->ru = trans('reasons.' . $value, [], 'ru');

        return $result;
    }
}

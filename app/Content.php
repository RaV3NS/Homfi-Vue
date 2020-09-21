<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**  @OA\Schema(
 *      schema="Content",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="body_ru",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="body_ua",
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

class Content extends Model
{
    protected $guarded = [];
}

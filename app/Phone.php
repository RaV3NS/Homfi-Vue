<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations\Items;

/**  @OA\Schema(
 *      schema="Phone",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @OA\Property(
 *          property="number",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="is_main",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="messengers",
 *          type="array",
 *          @Items(
 *              type="string"
 *          )
 *      )
 *   )
 *
 *  @OA\Schema(
 *      schema="CreatePhone",
 *      @OA\Property(
 *          property="number",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="is_main",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="messengers",
 *          type="array",
 *          @Items(
 *              type="string"
 *          )
 *      )
 *   )
 *
 * @OA\Schema(
 *      schema="SocialLinks",
 *      @OA\Property(
 *          property="email",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="skype",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="facebook",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="instagram",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="facebook_messenger",
 *          type="integer"
 *      )
 * )
 */

class Phone extends Model
{
    protected $guarded = [];

    protected $casts = [
        'messengers' => 'array'
    ];

    protected $visible = [
        'id',
        'number',
        'is_main',
        'messengers'
    ];
}

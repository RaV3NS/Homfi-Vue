<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**  @OA\Schema(
 *      schema="Parameter",
 *      @OA\Property(
 *          property="id",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="type",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="value",
 *          type="object",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="key",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="value_uk",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="value_ru",
 *                      type="string"
 *                  )
 *              )
 *      ),
 *      @OA\Property(
 *          property="is_required",
 *          type="boolean"
 *      ),
 *      @OA\Property(
 *          property="allowed_values",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="key",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="value_uk",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="value_ru",
 *                      type="string"
 *                  )
 *              )
 *          )
 *      ),
 *      @OA\Property(
 *          property="unit",
 *          type="object",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="ru",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="uk",
 *                      type="string"
 *                  )
 *              )
 *          )
 *      )
 *   )
 */

class Parameter extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'allowed_values' => 'array'
    ];

    protected $hidden = ['pivot'];

    public static $types = ['number', 'select', 'string', 'range'];

    public static $heatings = ['central', 'autonomous'];

    public function getNameAttribute()
    {
        return trans('parameters.' . $this->key);
    }

    public function getValueAttribute()
    {
        if(!empty($this->pivot)){
            $value = $this->pivot->value;

            if(is_array(trans('parameter_values.' . $this->key))){
                $value_uk = trans('parameter_values.' . $this->key . '.' . $value, [], 'uk');
                $value_ru = trans('parameter_values.' . $this->key . '.' . $value, [], 'ru');
            } else {
                $value_uk = $value;
                $value_ru = $value;
            }

            $return = [
                'key' => $value,
                'value_uk' => $value_uk,
                'value_ru' => $value_ru
            ];

            return $return;
        }
    }

    public function getAllowedValuesAttribute($value)
    {
        $result = [];
        $allowedValues = json_decode($value);

        if(is_array($allowedValues) && count($allowedValues)) {
            foreach($allowedValues as $allowedValue) {
                if(is_array(trans('parameter_values.' . $this->key . '.url'))) {
                    $param['key'] = trans('parameter_values.' . $this->key . '.url.' . $allowedValue);

                } elseif(is_array( trans('parameter_values.' . $this->key))) {
                    $param['key'] = $allowedValue;
                } else {
                    $param['key'] = trans('parameter_values.' . $this->key . '.' . $allowedValue);
                }

                $param['code'] = (string) $allowedValue;
                $param['value_uk'] = trans('parameter_values.' . $this->key . '.' . $allowedValue, [], 'uk');
                $param['value_ru'] = trans('parameter_values.' . $this->key . '.' . $allowedValue, [], 'ru');

                $result[] = $param;
            }
        }

        return $result;
    }

    public function getUnitAttribute($value)
    {
        $ru = $uk = $value;

        if(!empty($value)) {
            $uk = trans('parameter_units.' . $value, [], 'uk');
            $ru = trans('parameter_units.' . $value, [], 'ru');
        }

        return [
            'uk' => $uk,
            'ru' => $ru
        ];
    }
}

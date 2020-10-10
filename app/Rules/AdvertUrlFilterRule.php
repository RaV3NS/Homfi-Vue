<?php

namespace App\Rules;

use App\Advert;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AdvertUrlFilterRule implements Rule
{
    protected $keys = [];
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $result = true;

        $url = parseUrl($value);
        if(!empty($url['query'])){
            $queryFilter = $url['query'];

            foreach($queryFilter as $param) {
                list($key, $value) = explode('=', $param);

                switch($key) {
                    case 'pricemonth_max':
                        $validator = Validator::make(['pricemonth_max' => $value], ['pricemonth_max' => ['max:10']]);
                        if($validator->fails()){
                            array_push($this->keys, 'pricemonth_max');
                            return false;
                        }
                        break;
                }
            }
        }

        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Wrong filter format keys:' . implode(', ', $this->keys);
    }
}

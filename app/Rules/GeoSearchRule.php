<?php

namespace App\Rules;

use App\City;
use Illuminate\Contracts\Validation\Rule;

class GeoSearchRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $filter = json_decode($value, true);

        if(!in_array(reset($filter['type']), City::$geo_types)) {
            return false;
        }

        if(!is_int($filter['id'])){
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Wrong geo search object format';
    }
}

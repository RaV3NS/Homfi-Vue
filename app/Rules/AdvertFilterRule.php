<?php

namespace App\Rules;

use App\Advert;
use Illuminate\Contracts\Validation\Rule;

class AdvertFilterRule implements Rule
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
        $filter = json_decode($value);
        if(!empty($filter)) {
            foreach(json_decode($value) as $f=>$v) {
                if(!in_array($f, Advert::$filters)) {
                    return false;
                }
                if($f === 'type'){
                    foreach($v as $value) {
                        if(!in_array($value, Advert::$types)) {
                            return false;
                        }
                    }
                }
            }
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
        return 'Wrong filter format';
    }
}

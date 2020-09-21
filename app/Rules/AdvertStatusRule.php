<?php

namespace App\Rules;

use App\Advert;
use App\Exceptions\ApiException;
use Illuminate\Contracts\Validation\Rule;

class AdvertStatusRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    protected $statuses = [
        Advert::STATUS_MODERATE,
        Advert::STATUS_DISABLED,
        Advert::STATUS_DRAFT,
        Advert::STATUS_REJECTED,
        Advert::STATUS_BLOCKED,
        Advert::STATUS_ENABLED,
    ];

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws ApiException
     */
    public function passes($attribute, $value)
    {
        try{
            if(!in_array($value, $this->statuses)) {
                return false;
            }

            if($value === Advert::STATUS_ENABLED) {
                $advertId = request()->route()->parameter('advert');
                $advert = Advert::find($advertId);

                if($advert->status !== Advert::STATUS_DISABLED OR $advert->prev_status !== Advert::STATUS_ENABLED) {
                    return false;
                }
            }
        } catch (\Exception $e) {
            throw new ApiException(400, 'Validation error');
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
        return 'Wrong status';
    }
}

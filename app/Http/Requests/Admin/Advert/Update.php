<?php

namespace App\Http\Requests\Admin\Advert;

use App\AdminUser;
use App\Advert;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user() instanceof AdminUser;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $statuses = [
            Advert::STATUS_ENABLED,
            Advert::STATUS_MODERATE,
            Advert::STATUS_DISABLED,
            Advert::STATUS_REJECTED,
            Advert::STATUS_ACCEPTED,
        ];

        return [
            'status' => [
                Rule::in($statuses)
            ],
            'price_month' => 'required|min:1',
            'city_id' => ['required', 'exists:cities,id'],
            'street_id' => ['required', 'exists:streets,id'],
            'administrative_id' => ['exists:administrative,id'],
            'subway_id' => ['exists:subways,id'],
            'address' => 'sometimes',
            'first_name' => 'sometimes',
            'last_name' => 'sometimes',
            'email' => 'email',
            'social_links.*' => 'sometimes',
            'phones' => ['array'],
            'body' => ['sometimes'],
        ];
    }
}

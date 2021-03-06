<?php

namespace App\Http\Requests\API\User\Advert;

use App\Http\Requests\API\ApiRequest;
use App\User;
use Illuminate\Support\Facades\Auth;

class CreateAddress extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        //return Auth::user() instanceof User && Auth::user()->status === User::STATUS_ACTIVE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'district_id' => ['required', 'exists:districts,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'administrative_id' => ['sometimes', 'exists:administrative,id'],
            'street_id' => ['required', 'exists:streets,id'],
            'subway_id' => ['sometimes', 'exists:subways,id'],
            'address' => ['sometimes', 'max:255']
        ];
    }
}

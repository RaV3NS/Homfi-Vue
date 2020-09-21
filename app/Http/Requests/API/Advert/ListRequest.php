<?php

namespace App\Http\Requests\API\Advert;

use App\Advert;
use App\Http\Requests\API\ApiRequest;
use App\Rules\AdvertFilterRule;
use App\Rules\GeoSearchRule;
use Illuminate\Contracts\Validation\Rule;

class ListRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'city_id' => [
                'required',
                'integer',
                'exists:cities,id'
            ],
            'filter' => [
                'sometimes',
                'json',
                new AdvertFilterRule
            ],
            'order' => [
                'sometimes',
                'string',
                'in:lowest_price,highest_price,newest'
            ],
            'query' => [
                'sometimes',
                'string',
                'max: 255'
            ],
            'page' => ['sometimes', 'integer']
        ];
    }
}

<?php

namespace App\Http\Requests\API\Geo;

use App\Http\Requests\API\ApiRequest;
use Illuminate\Validation\Rule;

class CityRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'district_id' => ['sometimes', 'exists:districts,id'],
            'query' => [
                'required_without:district_id',
                'string'
            ],
        ];
    }
}

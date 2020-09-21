<?php

namespace App\Http\Requests\API\Geo;

use App\Http\Requests\API\ApiRequest;

class SearchRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'query' => ['sometimes', 'string'],
            'type' => ['sometimes', 'string', 'in:streets,administratives,subways'],
        ];
    }
}

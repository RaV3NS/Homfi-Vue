<?php

namespace App\Http\Requests\API\Content;

use App\Http\Requests\API\ApiRequest;

class ListRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => ['sometimes', 'integer'],
        ];
    }
}

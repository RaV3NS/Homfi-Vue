<?php

namespace App\Http\Requests\API\User\Favorite;

use App\Http\Requests\API\ApiRequest;

class ListRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer'],
        ];
    }
}

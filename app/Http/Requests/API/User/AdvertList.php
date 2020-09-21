<?php

namespace App\Http\Requests\API\User;

use App\Advert;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Validation\Rule;

class AdvertList extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer'],
            'status' => ['sometimes', Rule::in(Advert::$statuses)],
        ];
    }
}

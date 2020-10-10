<?php

namespace App\Http\Requests\API\Advert;

use App\Http\Requests\API\ApiRequest;
use App\Rules\AdvertUrlFilterRule;

class UrlListRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'url' => ['required', 'url', new AdvertUrlFilterRule]
        ];
    }
}

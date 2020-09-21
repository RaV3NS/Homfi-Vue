<?php

namespace App\Http\Requests\API\Advert;

use App\Advert;
use App\Http\Requests\API\ApiRequest;
use App\Rules\AdvertFilterRule;
use App\Rules\GeoSearchRule;
use Illuminate\Contracts\Validation\Rule;

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
            'url' => ['required', 'url']
        ];
    }
}

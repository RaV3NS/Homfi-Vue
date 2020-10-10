<?php declare(strict_types=1);

/**
 * Update Advert Request
 *
 * @author  Sviatoslav Poliakov <s.polyakov@dinarys.com>
 * @package App\Http\Requests\API\User
 */

namespace App\Http\Requests\API\User\Advert;

use App\Advert;
use App\Http\Requests\API\ApiRequest;
use App\Rules\AdvertStatusRule;
use Illuminate\Validation\Rule;

/**
 * Class Update
 */
class Update extends ApiRequest
{

    public function authorize()
    {
        //return auth('api')->user()->id == request()->route()->user;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => [new AdvertStatusRule],
            'type' => [
                'sometimes',
                Rule::in(Advert::$types)
            ],
            'city_id' => ['sometimes', 'exists:cities,id'],
            'street_id' => ['sometimes', 'exists:streets,id'],
            'subway_id' => ['nullable', 'exists:subways,id'],
            'administrative_id' => ['nullable', 'exists:administrative,id'],
            'price_month' => ['sometimes', 'numeric'],
            'first_name' => ['sometimes'],
            'last_name' => ['sometimes'],
            'email' => ['email'],
            'social_links' => ['sometimes', 'array'],
            'phones' => ['sometimes'],
            'phones.*.number' => ['required_with:phones'],
            'lat' => ['sometimes', 'numeric'],
            'lng' => ['sometimes', 'numeric'],
            'body' => ['sometimes'],
            'show_contacts' => ['sometimes'],
            'address' => ['sometimes'],
            'editing' => ['sometimes'],
            'room_count' => ['sometimes'],

            'parameters.*' => ['array'],
            'parameters.*.key' => ['exists:parameters,key'],

            'options' => ['array'],
        ];
    }
}

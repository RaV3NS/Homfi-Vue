<?php

namespace App\Http\Requests\API\User\Advert;

use App\Http\Requests\API\ApiRequest;
use App\User;
use Illuminate\Support\Facades\Auth;

class CreateContact extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user() instanceof User && Auth::user()->status === User::STATUS_ACTIVE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['sometimes'],
            'last_name' => ['sometimes'],
            'phones'=>['required', 'array'],
            'email' => ['sometimes', 'email'],
            'social_links' => ['sometimes', 'array']
        ];
    }
}

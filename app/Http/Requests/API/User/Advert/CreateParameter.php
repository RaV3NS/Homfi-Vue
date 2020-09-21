<?php

namespace App\Http\Requests\API\User\Advert;

use App\Http\Requests\API\ApiRequest;
use App\User;
use Illuminate\Support\Facades\Auth;

class CreateParameter extends ApiRequest
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
            'parameters.*' => ['required'],
            'parameters.*.key' => ['exists:parameters,key'],
            'body' => ['sometimes', 'max:3000'],
            'price_month' => ['sometimes', 'numeric']
        ];
    }
}

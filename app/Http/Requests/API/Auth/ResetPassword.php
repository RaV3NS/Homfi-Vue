<?php

namespace App\Http\Requests\API\Auth;

use App\Http\Requests\API\ApiRequest;

class ResetPassword extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }
}

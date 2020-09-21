<?php


namespace App\Http\Requests\API\Auth;

use App\Http\Requests\API\ApiRequest;

class Login extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'remember_me' => ['sometimes', 'boolean'],
        ];
    }
}

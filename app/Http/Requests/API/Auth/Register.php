<?php


namespace App\Http\Requests\API\Auth;

use App\Http\Requests\API\ApiRequest;

class Register extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'lang' => ['string', 'in:uk,ru']
        ];
    }
}

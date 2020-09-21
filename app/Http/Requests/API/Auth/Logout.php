<?php


namespace App\Http\Requests\API\Auth;

use App\Http\Requests\API\ApiRequest;

class Logout extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'token' => ['required'],
        ];
    }
}

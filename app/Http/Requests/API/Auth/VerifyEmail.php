<?php

namespace App\Http\Requests\API\Auth;

use App\Http\Requests\API\ApiRequest;

class VerifyEmail extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'hash' => ['required'],
            'id' => ['required', 'numeric'],
            'signature' => ['required'],
            'expires' => ['required'],
        ];
    }
}

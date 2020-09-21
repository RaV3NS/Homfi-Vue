<?php

namespace App\Http\Requests\API\User;

use App\Http\Requests\API\ApiRequest;

class NotificationList extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer'],
        ];
    }
}

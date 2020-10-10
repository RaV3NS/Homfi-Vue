<?php

namespace App\Http\Requests\API\User\Advert;

use App\Http\Requests\API\ApiRequest;
use App\User;
use Illuminate\Support\Facades\Auth;

class CreatePhoto extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return Auth::user() instanceof User && Auth::user()->status === User::STATUS_ACTIVE;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photos.*' => ['required', 'image'],
            'rotation' => ['required', 'array'],
            'rotation.*' => ['sometimes', 'numeric'],
        ];
    }
}

<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Coordinates extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'city_id' => 'required|exists:cities,id',
            'street_id' => 'required|exists:streets,id',
            'address' => 'nullable|string',
            'lang' => ['nullable', Rule::in(['ru', 'uk', 'ua'])]
        ];
    }
}

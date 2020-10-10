<?php


namespace App\Http\Requests\API\Auth;

use App\Http\Requests\API\ApiRequest;

class ChangePassword extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ];
    }

    public function messages()
    {
        return [
           'confirm_password.same' => trans('auth.not_the_same_password')
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => trans('validation.attributes.old_password'),
            'new_password' => trans('validation.attributes.new_password'),
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\Advert;

use App\AdminUser;
use App\Advert;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateStatus extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user() instanceof AdminUser;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userLog = request()->get('user_log');

        return [
            'status' => [
                'required',
                Rule::in(Advert::$statuses)
            ],
            'user_log.title' => 'sometimes|string',
            'user_log.body' => Rule::requiredIf($userLog['title'] ===  trans('adminlte::admin.user.reason.another'))
        ];
    }
}

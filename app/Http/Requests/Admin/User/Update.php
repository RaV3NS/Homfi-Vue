<?php

namespace App\Http\Requests\Admin\User;

use App\AdminUser;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Update extends FormRequest
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
        $userStatuses = [
            User::STATUS_ACTIVE,
            User::STATUS_BLOCKED,
            User::STATUS_DISABLED,
            User::STATUS_DELETED,
        ];
        $userLog = request()->get('user_log');

        return [
            'status' => [
                'required',
                Rule::in($userStatuses)
            ],
            'user_log.title' => 'sometimes',
            'user_log.body' => Rule::requiredIf($userLog['title'] ===  trans('adminlte::admin.user.reason.another_reason'))
        ];
    }
}

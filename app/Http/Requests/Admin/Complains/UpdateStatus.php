<?php

namespace App\Http\Requests\Admin\Complains;

use App\AdminUser;
use App\Complain;
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
        return [
            'status' => [
                'required',
                Rule::in([Complain::STATUS_REJECTED, Complain::STATUS_PENDING, Complain::STATUS_SOLVED])
            ],
            'body' => 'sometimes|min:2'
        ];
    }
}

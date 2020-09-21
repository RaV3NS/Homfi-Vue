<?php

namespace App\Http\Requests\Admin\Complains;

use App\AdminUser;
use App\Complain;
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
        return [
            'status' => [
                Rule::in(Complain::$statuses)
            ],
            'name' => 'required|min:2',
            'email' => 'email',
        ];
    }
}

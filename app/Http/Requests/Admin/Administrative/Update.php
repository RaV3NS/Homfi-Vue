<?php

namespace App\Http\Requests\Admin\Administrative;

use App\AdminUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'city_id' => 'required|exists:cities,id',
            'name_ru' => 'required|string|max:255',
            'name_uk' => 'required|string|max:255',
            'old_name_ru' => 'nullable|string|max:255',
            'old_name_uk' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'city_id.required' => 'Обязательное поле для заполнения',
            'name_ru.required' => 'Обязательное поле для заполнения',
            'name_uk.required' => 'Обязательное поле для заполнения',
        ];
    }
}

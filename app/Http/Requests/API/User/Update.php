<?php declare(strict_types=1);

/**
 * Update User Request
 *
 * @author  Sviatoslav Poliakov <s.polyakov@dinarys.com>
 * @package App\Http\Requests\API\User
 */

namespace App\Http\Requests\API\User;

use App\Http\Requests\API\ApiRequest;
use App\Rules\PhoneNumber;
use App\User;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 */
class Update extends ApiRequest
{

    public function authorize()
    {
        return true;
        //return auth('api')->user()->id == request()->route()->user;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $userStatuses = [
            User::STATUS_ACTIVE,
            User::STATUS_BLOCKED,
            User::STATUS_DISABLED,
            User::STATUS_DELETED,
        ];

        return [
            'first_name' => ['sometimes'],
            'last_name' => ['sometimes'],
            'patronymic' => ['sometimes'],
            'email' => ['sometimes', 'email', 'unique:users'],
            'phones' => ['sometimes','nullable','array'],
            'phones.*.number' => ['required', new PhoneNumber],
            'phones.*.messengers' => ['sometimes', 'array'],
            'social_links' => ['sometimes','nullable','array'],
            'social_links.email' => ['email'],
            'status' => [
                'sometimes',
                Rule::in($userStatuses)
            ],
        ];
    }
}

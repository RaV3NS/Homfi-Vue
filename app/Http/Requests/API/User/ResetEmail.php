<?php declare(strict_types=1);

/**
 * Reset User Email Request
 *
 * @author  Sviatoslav Poliakov <s.polyakov@dinarys.com>
 * @package App\Http\Requests\API\User
 */

namespace App\Http\Requests\API\User;

use App\Http\Requests\API\ApiRequest;
use App\User;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 */
class ResetEmail extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'min:6'],
            'email' => ['required', 'email', 'unique:users']
        ];
    }
}

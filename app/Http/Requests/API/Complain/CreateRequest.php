<?php declare(strict_types=1);

/**
 * Create Complain Request
 *
 * @author  Sviatoslav Poliakov <s.polyakov@dinarys.com>
 * @package App\Http\Requests\API\Complains
 */

namespace App\Http\Requests\API\Complain;

use App\Http\Requests\API\ApiRequest;
use App\Rules\PhoneNumber;

/**
 * Class CreateRequest
 */
class CreateRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['email'],
            'phone' => [
                new PhoneNumber,
                'numeric',
                'min:10',
            ],
            'user_id' => ['sometimes', 'exists:users,id'],
            'reason' => ['required', 'string', 'min:2'],
            'body' => ['sometimes', 'string', 'min:2'],
        ];
    }
}

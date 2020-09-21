<?php declare(strict_types=1);

/**
 * Update Notification Request
 *
 * @author  Sviatoslav Poliakov <s.polyakov@dinarys.com>
 * @package App\Http\Requests\API\User
 */

namespace App\Http\Requests\API\User;

use App\Http\Requests\API\ApiRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateNotification
 */
class UpdateNotification extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $types = [ 'new', 'read'];

        return [
            'status' => [
                'required',
                Rule::in($types)
            ]
        ];
    }
}

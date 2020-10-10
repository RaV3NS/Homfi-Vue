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
class Delete extends ApiRequest
{
    public function authorize()
    {
        return true;
        //return auth('api')->user()->id == request()->route()->user;
    }

    public function rules()
    {
        return [];
    }
}

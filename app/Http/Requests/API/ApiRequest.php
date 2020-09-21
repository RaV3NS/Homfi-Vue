<?php

/**
 * Api Request
 *
 * @author  Sviatoslav Poliakov <s.polyakov@dinarys.com>
 * @package App\Http\Requests\API
 */

namespace App\Http\Requests\API;

use App\Exceptions\ApiException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class ApiRequest
 */
abstract class ApiRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws ApiException
     */
    public function failedValidation(Validator $validator)
    {
        throw new ApiException($validator->getMessageBag()->toArray());
    }
}


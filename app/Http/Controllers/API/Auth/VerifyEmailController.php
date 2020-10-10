<?php

namespace App\Http\Controllers\API\Auth;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\VerifyEmail;
use App\User;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *      schema="VerifyEmailError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="hash",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="signature",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="expires",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */
class VerifyEmailController extends Controller
{
    /**
     * @OA\Post(
     *     path="/verify-email",
     *     tags={"Auth"},
     *     summary="Verify User email",
     *     operationId="verify_email",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="id",
     *                    required={"true"},
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="hash",
     *                    required={"true"},
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="signature",
     *                    required={"true"},
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="expires",
     *                    required={"true"},
     *                    type="string"
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email verified successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/VerifyEmailError"
     *             )
     *         )
     *     )
     * )
     */

    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Validate url and verify user email
     *
     * @param VerifyEmail $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(VerifyEmail $request)
    {
        try {
            if (!$user = User::find($request->get('id'))) {
                throw new ApiException('Can\'t verify email', 400);
            }

            if (!hash_equals((string)$request->get('hash'), sha1($user->getEmailForVerification()))) {
                throw new ApiException('Can\'t verify email', 400);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json([], 204);
            }

            if ($user->markEmailAsVerified()) {
                $user->update(['status' => User::STATUS_ACTIVE]);
                event(new Verified($user));
            } else {
                throw new ApiException('Can\'t verify email', 400);
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            throw new ApiException('Can\'t verify email', 400);
        }

        return response()->json([], 200);
    }
}

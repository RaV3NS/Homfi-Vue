<?php

namespace App\Http\Controllers\API\Auth;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\ResetPassword;
use App\User;
use Exception;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *      schema="ResetPasswordError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="token",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="password",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="password_confirmation",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class ResetPasswordController extends Controller
{
    use ResetsPasswords;
    /**
     * @OA\Post(
     *     path="/reset-password",
     *     tags={"Auth"},
     *     summary="Reset User password ",
     *     operationId="reset_password",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="token",
     *                    required={"true"},
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="email",
     *                    required={"true"},
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="password",
     *                    required={"true"},
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="password_confirmation",
     *                    required={"true"},
     *                    type="string"
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/ResetPasswordError"
     *             )
     *         )
     *     )
     * )
     */

    /**
     * Send an email to reset user's password
     *
     * @param ResetPassword $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(ResetPassword $request)
    {
        try {
            // Validate the token
            $tokenData = DB::table('password_resets')
                ->where('email', $request->email)->first();

            $user = User::where('email', $tokenData->email)->first();


            if(!$tokenData || !$user){
                return response()->json([], 400);
            }

            if(!Hash::check($request->get('token'), $tokenData->token)) {
                return response()->json([], 400);
            }

            $password = $request->get('password');
            $this->setUserPassword($user, $password);
            $user->save();

            //Delete the token
            DB::table('password_resets')->where('email', $user->email)
                ->delete();

        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            throw new ApiException('Can\'t reset password', 400);
        }

        return response()->json([], 200);
    }

    protected function rules()
    {
        return [];
    }
}

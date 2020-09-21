<?php

namespace App\Http\Controllers\API\Auth;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\ChangePassword;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(
 *      schema="ChangePasswordError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="old_password",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="new_password",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="confirm_password",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class ChangePasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/change-password",
     *     tags={"Auth"},
     *     summary="Change User password ",
     *     operationId="change_password",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="old_password",
     *                    required={"true"},
     *                    type="string",
     *                ),
     *                @OA\Property(
     *                    property="new_password",
     *                    required={"true"},
     *                    type="string",
     *                ),
     *                @OA\Property(
     *                    property="confirm_password",
     *                    required={"true"},
     *                    type="string",
     *                ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/ForgotPasswordError"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Unauthorized"
     *             )
     *         )
     *     ),
     *     security={
     *         {"JWT": {}}
     *     }
     * )
     */

    /**
     * Change user's password
     *
     * @param ChangePassword $request
     * @return JsonResponse
     */
    public function execute(ChangePassword $request)
    {
        $validated = $request->validated();
        try {
            if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                $response = ["status" => 400, "message" => trans('auth.wrong_old_password')];
            } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                $response = ["status" => 400, "message" => trans('auth.password_similar_as_old')];
            } else {
                User::where('id', Auth::guard('api')->user()->id)->update(['password' => Hash::make($validated['new_password'])]);
                $response = ["status" => 200, "message" => trans('auth.password_updated')];
            }
        } catch (\Exception $ex) {
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            } else {
                $msg = $ex->getMessage();
            }
            $response = ["status" => 400, "message" => $msg];
        }

        return response()->json($response);
    }

    protected function getEmailSubject() {
        return trans('auth.reset_password_subject');
    }
}

<?php

namespace App\Http\Controllers\API\Auth;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\ForgotPassword;
use App\User;
use Exception;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 *      schema="ForgotPasswordError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="email",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected $broker = 'users';

    /**
     * @OA\Post(
     *     path="/forgot-password",
     *     tags={"Auth"},
     *     summary="Forgot User password ",
     *     operationId="forgot_password",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="email",
     *                    required={"true"},
     *                    type="string",
     *                    format="email",
     *                    example="some@gmail.com",
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reset Link sended successfully"
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
     * Send an email to reset user's password
     *
     * @param ForgotPassword $request
     * @return JsonResponse
     */
    public function execute(ForgotPassword $request)
    {
        try {
            ResetPassword::createUrlUsing(function($data, $token){
                return config('settings.restore_password_url') . '?token=' . $token . '&email=' . urlencode($data->email);
            });

            $broker = $this->broker();
            try{
                $user = User::where('email', $request->get('email'))->firstOrFail();

                $user->sendPasswordResetNotification($broker->createToken($user));
            } catch(Exception $e) {
                throw new ApiException('Cant reset password', 400);
            }
        }catch (Exception $ex) {
            return response()->json($ex->getMessage(), 400);
        }

        return response()->json([], 200);
    }

    protected function getEmailSubject() {
        return trans('auth.reset_password_subject');}
}

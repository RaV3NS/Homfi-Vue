<?php


namespace App\Http\Controllers\API\User;

use App\Events\EmailChanged;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\ResetEmail;
use App\Http\Requests\API\User\Update;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *      schema="ResetEmailError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="password",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="email",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */
class ResetEmailController extends Controller
{
    /**
     * @OA\POST(
     *     path="/user/{user_id}/reset-email",
     *     tags={"User"},
     *     summary="Reset user email",
     *     operationId="reset_email",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="email",
     *                      required={"true"},
     *                      type="string",
     *                       example="user@email.com"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      required={"true"},
     *                      type="string",
     *                      minLength=6,
     *                      example="123456",
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/ResetEmailError"
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
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/CommonError"
     *             )
     *         )
     *     ),
     *     security={
     *         {"JWT": {}}
     *     }
     * )
     */

    /**
     * Reset user email
     *
     * @param int $userId
     * @param ResetEmail $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, ResetEmail $request): JsonResponse
    {
        try {
            $user = User::findOrFail($userId);
            $validated = $request->validated();

            $credentials = [
                'email' => $user->email,
                'password' => $validated['password']
            ];

            if (Auth::validate($credentials)) {
                event(new EmailChanged($user));
                $user->update(['email' => $validated['email'], 'email_verified_at' => NULL]);
            } else {
                throw new ApiException("Password not match", 400);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException("Can't reset email", 400);
        }

        return response()->json();
    }
}

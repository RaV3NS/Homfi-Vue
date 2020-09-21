<?php


namespace App\Http\Controllers\API\User;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Update;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *      schema="UpdateUserError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="first_name",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="last_name",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="email",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="phones",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="social_links",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="status",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class UpdateController extends Controller
{
    /**
     * @OA\Put(
     *     path="/user/{user_id}",
     *     tags={"User"},
     *     summary="Update user by id",
     *     operationId="user_update",
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
     *                      property="first_name",
     *                      required={"true"},
     *                      type="string",
     *                      minLength=2,
     *                      example="John",
     *                  ),
     *                  @OA\Property(
     *                      property="last_name",
     *                      required={"true"},
     *                      type="string",
     *                      minLength=2,
     *                      example="Doe",
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="phones",
     *                      type="object",
     *                      @OA\Schema(
     *                          ref="#/components/schemas/Phone"
     *                      ),
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="social_links",
     *                      type="object",
     *                       @OA\Schema(
     *                          ref="#/components/schemas/SocialLinks"
     *                      ),
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="status",
     *                      type="string",
     *                      nullable=true
     *                  ),
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/UpdateUserError"
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
     * Get User
     *
     * @param int $userId
     * @param Update $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, Update $request): JsonResponse
    {
        try {
            $user = User::findOrFail($userId);
            $validated = $request->validated();

            $user->update($validated);

            if(!empty($validated['phones'])) {
                $user->phones()->delete();
                foreach($validated['phones'] as $phone) {
                    $phone['model'] = User::class;
                    $phone['model_id'] = $userId;
                    $user->phones()->create($phone);
                }
            }

            $user->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException("Can't update user", 400);
        }

        return response()->json();
    }
}

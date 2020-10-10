<?php


namespace App\Http\Controllers\API\User;


use App\Advert;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Delete;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *      schema="DeleteUserError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="user_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class DeleteController extends Controller
{
    /**
     * @OA\Delete(
     *     path="/user/{user_id}",
     *     tags={"User"},
     *     summary="Delete User",
     *     operationId="delete_user",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="No Content"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/DeleteUserError"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Access Denied",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/CommonError"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/CommonError"
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
     * Delete user
     *
     * @return JsonResponse
     * @throws ApiException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function execute(int $userId, Delete $request): JsonResponse
    {
        $user = User::query()->findOrFail($userId);
        try {
            $user->phones()->delete();
            $user->adverts()->each(function($advert){
               $advert->update(['status'=>Advert::STATUS_DISABLED]);
            });
            $user->complains()->delete();
            $user->favorites()->delete();

            $user->update(['status' => User::STATUS_DELETED]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Can\'t delete user', 400);
        }

        return response()->json([], 200);
    }
}

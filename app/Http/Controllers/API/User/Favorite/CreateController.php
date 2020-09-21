<?php


namespace App\Http\Controllers\API\User\Favorite;

use App\Exceptions\ApiException;
use App\Favorite;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Favorite\CreateRequest;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *      schema="CreateFavoriteError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="advert_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class CreateController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/{user_id}/favorites",
     *     tags={"User"},
     *     summary="Add favorite advert to user",
     *     operationId="favorite_create",
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
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="advert_id",
     *                    type="integer"
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="No Content"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/CreateFavoriteError"
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
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, CreateRequest $request): JsonResponse
    {
        $user = User::query()->findOrFail($userId);
        $advertId = $request->input('advert_id');
        try {
            $user->favorites()->updateOrInsert(
                ['user_id' => $userId, 'advert_id' => $advertId],
                ['user_id' => $userId, 'advert_id' => $advertId]
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Can\'t add to favorite', 400);
        }

        return response()->json();
    }
}

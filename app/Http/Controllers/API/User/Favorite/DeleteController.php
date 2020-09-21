<?php


namespace App\Http\Controllers\API\User\Favorite;

use App\Exceptions\ApiException;
use App\Favorite;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DeleteController extends Controller
{
    /**
     * @OA\Delete(
     *     path="/user/{user_id}/favorites/{advert_id}",
     *     tags={"User"},
     *     summary="Delete user favorite advert by id",
     *     operationId="favorite_delete",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="advert_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="No Content"
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
     * @param int $advertId
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, int $advertId): JsonResponse
    {
        $favorite = Favorite::where('user_id', $userId)->where('advert_id', $advertId);
        try {
            $favorite->delete();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Can\'t delete favorite', 400);
        }

        return response()->json();
    }
}

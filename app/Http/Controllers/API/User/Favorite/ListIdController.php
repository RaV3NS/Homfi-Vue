<?php

namespace App\Http\Controllers\API\User\Favorite;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;

class ListIdController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/{user_id}/favorites/ids",
     *     tags={"User"},
     *     summary="List All User Favorites Ids",
     *     operationId="user_favorites_ids_list",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User favorites ids received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                type="array",
     *                @OA\Items(
     *                    type="integer"
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=204, description="No Content"),
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
     *     security={
     *         {"JWT": {}}
     *     }
     * )
     */

    /**
     * Get favorites list
     *
     * @param int $userId
     * @return Paginator
     */
    public function execute(int $userId): JsonResponse
    {
        $favorites = User::query()->findOrFail($userId)->favorites()->pluck('advert_id');

        return response()->json($favorites);
    }
}

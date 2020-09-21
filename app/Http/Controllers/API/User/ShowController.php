<?php


namespace App\Http\Controllers\API\User;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/{user_id}",
     *     tags={"User"},
     *     summary="Returns user by id",
     *     operationId="user",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User profile received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/UserProfile"
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
     * Get User
     *
     * @return JsonResponse
     * @throws ApiException
     * @throws ModelNotFoundException
     */
    public function execute(int $userId): JsonResponse
    {
        $user = User::query()->with(['phones'])->findOrFail($userId);

        return response()->json($user);
    }
}

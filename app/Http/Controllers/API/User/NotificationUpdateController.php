<?php

namespace App\Http\Controllers\API\User;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\UpdateNotification;
use App\User;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="UserNotificationUpdateError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="type",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class NotificationUpdateController extends Controller
{
    /**
     * @OA\Put(
     *     path="/user/{user_id}/notifications/{notification_id}",
     *     tags={"User"},
     *     summary="Update User Notification",
     *     operationId="update_user_notifications",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="notification_id",
     *         in="path",
     *         description="Notification id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="status",
     *                    required={"true"},
     *                    type="string"
     *                ),
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="Advert updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/UserNotificationUpdateError"
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
     * Update notification
     *
     * @param int $userId
     * @param int $notificationId
     * @param UpdateNotification $request
     * @return JsonResponse
     */
    public function execute(int $userId, int $notificationId, UpdateNotification $request): JsonResponse
    {
        $notification = User::findOrFail($userId)->notifications()->findOrFail($notificationId);
        $notification->update($request->validated());

        return response()->json();
    }
}

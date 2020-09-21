<?php

namespace App\Http\Controllers\API\User;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\NotificationList;
use App\User;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * @OA\Schema(
 *      schema="UserNotificationsListError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="page",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class NotificationListController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/{user_id}/notifications",
     *     tags={"User"},
     *     summary="List All User Notifications",
     *     operationId="user_notifications_list",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number with results",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User notifications received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="current_page",
     *                     type="integer",
     *                     format="int32"
     *                 ),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         ref="#/components/schemas/Notification"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="first_page_url",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="from",
     *                     type="integer",
     *                     format="int32"
     *                 ),
     *                 @OA\Property(
     *                     property="next_page_url",
     *                     type="string",
     *                     nullable=true
     *                 ),
     *                 @OA\Property(
     *                     property="path",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="per_page",
     *                     type="integer",
     *                     format="int32"
     *                 ),
     *                 @OA\Property(
     *                     property="prev_page_url",
     *                     type="string",
     *                     nullable=true
     *                 ),
     *                 @OA\Property(
     *                     property="to",
     *                     type="integer",
     *                     format="int32"
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     type="integer",
     *                     format="int32"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=204, description="No Content"),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/UserNotificationsListError"
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
     *     security={
     *         {"JWT": {}}
     *     }
     * )
     */

    /**
     * Get notifications list
     *
     * @param int $userId
     * @param NotificationList $request
     * @return Paginator
     */
    public function execute(int $userId, NotificationList $request): Paginator
    {
        return User::findOrFail($userId)->notifications()
            ->with(['advert'])
            ->without(['owner'])
            ->paginate();
    }
}

<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\AdvertList;
use App\User;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * @OA\Schema(
 *      schema="UserAdvertsListError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="user_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ), @OA\Property(
 *              property="status",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="page",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class ListController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/{user_id}/adverts",
     *     tags={"User"},
     *     summary="List All User Adverts",
     *     operationId="user_adverts_list",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Advert status filter",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"draft", "disabled", "enabled", "moderate", "rejected", "accepted", "blocked"}
     *         )
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
     *         description="User adverts received successfully",
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
     *                         ref="#/components/schemas/PublicAdvert"
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
     *                 ref="#/components/schemas/UserAdvertsListError"
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
     * Get adverts list
     *
     * @return Paginator
     * @throws ApiException
     */
    public function execute(int $userId, AdvertList $request): Paginator
    {
        $adverts = User::findOrFail($userId)
            ->adverts()
            ->with(['notification'])
            ->without('user', 'owner')
            ->orderBy('updated_at', 'desc');

        if($status = $request->get('status')) {
            $adverts->where('status', $status);
        }

        $result = $adverts->paginate();
        $result->map(function($advert) {
            $advert->getImageUrls();
            unset($advert->media);
        });

        return $result;
    }
}

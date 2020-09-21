<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Advert;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Advert\Create;
use App\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="UserAdvertCreateError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="district_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="city_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="administrative_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="street_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="subway_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="address",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="lat",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="lng",
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
     *     path="/user/{user_id}/adverts",
     *     tags={"Create Advert"},
     *     summary="Create User Advert",
     *     operationId="create_user_advert",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="district_id",
     *                    required={"true"},
     *                    type="integer"
     *                ),
     *                @OA\Property(
     *                    property="city_id",
     *                     required={"true"},
     *                    type="integer"
     *                ),
     *                @OA\Property(
     *                    property="administrative_id",
     *                    type="integer"
     *                ),
     *                @OA\Property(
     *                    property="street_id",
     *                    required={"true"},
     *                    type="integer"
     *                ),
     *                @OA\Property(
     *                    property="address",
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="subway_id",
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="lat",
     *                    required={"false"},
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="lng",
     *                    required={"false"},
     *                    type="string"
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Advert created successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Advert"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/UserAdvertCreateError"
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
     * Create advert
     *
     * @param int $userId
     * @param Create $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, Create $request): JsonResponse
    {
        $user = User::findOrFail($userId);

        try {
            $advert = $request->validated();
            $advert['first_name'] = $user->first_name;
            $advert['last_name'] = $user->last_name;
            $advert['email'] = $user->email;
            $advert['status'] = Advert::STATUS_DRAFT;

            if(empty($advert['lng']) OR empty($advert['lat'])) {
                $advert['lat'] = 0;
                $advert['lng'] = 0;
            }

            $advert['body'] = $advert['social_links'] = '';
            $advert['room_count'] = 0;

            unset($advert['district_id']);

            $advert = $user->adverts()->create($advert);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Cant create advert', 400);
        }

        return response()->json($advert);
    }

}

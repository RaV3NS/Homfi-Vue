<?php

namespace App\Http\Controllers\API\User\Advert;

use App\City;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Advert\CreateAddress;
use App\Jobs\GetCoordinates;
use App\OsmQueue;
use App\Street;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="AdvertAddressError",
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
 *          )
 *      )
 *  )
 */

class AddressController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/{user_id}/adverts/address",
     *     tags={"Create Advert"},
     *     summary="Create Advert address",
     *     operationId="create_advert_address",
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
     *                    required={"true"},
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
     *                    type="integer"
     *                ),
     *                @OA\Property(
     *                    property="subway_id",
     *                    type="string"
     *                ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Advert address resolved successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Coordinate"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/AdvertAddressError"
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
     * @param CreateAddress $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(CreateAddress $request): JsonResponse
    {
        try {
            $city = City::query()->findOrFail($request->input('city_id'));
            $street = Street::query()->select('id', 'name_uk', 'prefix')->findOrFail($request->input('street_id'));
            $uuid = Str::uuid();
            $address = preg_replace('/^\D+/u', '', $request->input('address'));
            $prefix = Street::getNormalizePrefix($street->prefix);
            $streetString = join(' ', [$street->getRawOriginal('name_uk'), $prefix]);
            GetCoordinates::dispatch($city->name_uk, $streetString, $address, $uuid,
                GetCoordinates::PHASE_WITH_LETTER, $street->id, app()->getLocale())->onQueue('coordinates');

            $i = 40;
            while ($i > 0) {
                $osmQueue = OsmQueue::query()->firstWhere('uuid', '=', $uuid);
                if ($osmQueue) {
                    break;
                } else {
                    $i--;
                    usleep(250000);
                }
            }
            if (isset($osmQueue) && $osmQueue) {
                $osmQueue->delete();
                return response()->json(['lat' => $osmQueue->lat, 'lng' => $osmQueue->lng]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage().' '.$e->getTraceAsString());
            throw new ApiException($e->getMessage());
        }

        return response()->json([], 404);
    }
}

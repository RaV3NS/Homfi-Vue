<?php

namespace App\Http\Controllers\API\Advert;

use App\Advert;
use App\Filters\AdvertFilter;
use App\Filters\GeoFilter;
use App\Filters\ParameterFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Advert\ListRequest;
use Illuminate\Http\JsonResponse;

class CoordinatesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/adverts/coordinates",
     *     tags={"Advert"},
     *     summary="List All Adverts Coordinates for choosen city",
     *     operationId="adverts_coordinates",
     *     @OA\Parameter(
     *         name="city_id",
     *         in="query",
     *         description="City ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="String to filter adverts by advert fields",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         example="{""type"":[""flat""],""price_month"":{""from"":10,""to"":1000},""room_count"":[1, 2]}"
     *     ),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search in advert body",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         example="Осокорки"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All adverts coordinates received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                     ref="#/components/schemas/AdvertCoordinate"
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
     *                 ref="#/components/schemas/AdvertsListError"
     *             )
     *         )
     *     )
     * )
     */

    /**
     * Get adverts coordinates list
     *
     * @param ListRequest $request
     * @param AdvertFilter $advertFilter
     * @param ParameterFilter $parameterFilters
     * @param GeoFilter $geoFilter
     * @return JsonResponse
     */
    public function execute(ListRequest $request, AdvertFilter $advertFilter, ParameterFilter $parameterFilters, GeoFilter $geoFilter): JsonResponse
    {
        $validated = $request->validated();
        $adverts = Advert::query()->select('adverts.id', 'lat', 'lng')
            ->where('city_id', '=', $validated['city_id'])
            ->where('status', '=', Advert::STATUS_ENABLED);

        $adverts->without(['user', 'city', 'phones']);
        $adverts->groupBy('adverts.id');

        $filter = [];
        if (!empty($validated['filter'])) {
            $filter = json_decode($validated['filter'], true);
        }

        if (!empty($filter)) {
            $adverts->advertFilter($advertFilter);

            $adverts->geoFilter($geoFilter);

            if ($parameterFilters->countFilters()) {
                $adverts->parameterFilter($parameterFilters);
                //$adverts->having('cnt', '>=', $parameterFilters->countFilters());
            }
        }

        if (!empty($validated['query'])) {
            $adverts->where('body', 'like', '%' . $validated['query'] . '%');
        }

        $result = $adverts->get();
        $result->each(function($advert){
            $advert->setHidden(['title', 'phone', 'full_address', 'city', 'street', 'subway', 'administrative']);
        });

        return response()->json($result);
    }
}

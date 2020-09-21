<?php

namespace App\Http\Controllers\API\Geo;

use App\City;
use App\Http\Requests\API\Geo\CityRequest;
use App\Region;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *      schema="SearchCityError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="query",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="district_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *     )
 * )
 * /
 *
 * /**
 * Class SearchController
 */
class CityController
{
    /**
     * @OA\Get(
     *     path="/geo/cities",
     *     tags={"Geo"},
     *     summary="Search city by keyword",
     *     operationId="city_search",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search query",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="district_id",
     *         in="query",
     *         description="District ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cities received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                    ref="#/components/schemas/City"
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
     *                 ref="#/components/schemas/SearchCityError"
     *             )
     *         )
     *     ),
     * )
     */

    /**
     * @param CityRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function execute(CityRequest $request)
    {
        $query = $request->get('query');
        $district_id = $request->get('district_id');

        $cities = City::with('region');

        if (!empty($district_id)) {
            $cities->whereIn('region_id', Region::where('district_id', $district_id)->get('id'));
        } else {
            $cities->withCount('activeAdverts as adverts_count')
                ->having('adverts_count', '>', '0');
        }

        if(!empty($query)) {
            $cities->where(function($builder) use ($query) {
                $builder->orWhere('name_uk', 'like', $query . '%')
                        ->orWhere('name_ru', 'like', $query . '%');
            });
        } else {
            $cities->limit(100);
        }

        $cities->orderBy('name_uk');

        $result = $cities->get();

        return response()->json($result);
    }
}

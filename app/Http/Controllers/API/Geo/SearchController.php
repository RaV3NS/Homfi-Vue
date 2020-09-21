<?php

namespace App\Http\Controllers\API\Geo;

use App\Administrative;
use App\Http\Requests\API\Geo\SearchRequest;
use App\Street;
use App\Subway;

/**
 * @OA\Schema(
 *      schema="SearchGeoError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="city_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="query",
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
class SearchController
{
    /**
     * @OA\Get(
     *     path="/geo/search",
     *     tags={"Geo"},
     *     summary="Geo objects in city by keyword",
     *     operationId="geo_search",
     *     @OA\Parameter(
     *         name="city_id",
     *         in="query",
     *         description="City id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
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
     *         name="type",
     *         in="query",
     *         description="Geo type",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *          example="streets"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Geo objects received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="streets",
     *                     type="array",
     *                     @OA\Items(
     *                         ref="#/components/schemas/SearchGeoObject"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="subways",
     *                     type="array",
     *                     @OA\Items(
     *                        ref="#/components/schemas/SearchGeoObject"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="administratives",
     *                     type="array",
     *                     @OA\Items(
     *                         ref="#/components/schemas/SearchGeoObject"
     *                     )
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
     *                 ref="#/components/schemas/SearchGeoError"
     *             )
     *         )
     *     ),
     * )
     */

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function execute(SearchRequest $request)
    {
        $validated = $request->validated();
        $query = $request->get('query');
        $city_id = $validated['city_id'];
        $geoType = $request->get('type');
        $result = [];

        if (!$geoType OR $geoType === 'streets') {
            $streets = Street::where('city_id', '=', $city_id);
            //select('id', 'prefix', 'prefix_ru', 'name_ru', 'name_uk', 'uuid', 'city_id', 'translit')

            if (!empty($query)) {
                $streets->where(function ($builder) use ($query) {
                    $builder->where('name_uk', 'like', $query . '%')
                        ->orWhere('name_ru', 'like', $query . '%');
                });
            } else {
                $streets->limit(100);
            }

            if (!$geoType) {
                $streets->withCount('activeAdverts as advert_count')
                    ->having('advert_count', '>', '0');
            }

            $result['streets'] = $streets->get();
        }

        if (!$geoType OR $geoType === 'subways') {
            $subways = Subway::query()->select('id', 'name_ru', 'name_uk', 'translit')
                ->where('city_id', $city_id);

            if (!empty($query)) {
                $subways->where(function ($builder) use ($query) {
                    $builder->where('name_uk', 'like', $query . '%')
                        ->orWhere('name_ru', 'like', $query . '%');
                });
            } else {
                $subways->limit(100);
            }

            if (!$geoType) {
                $subways->withCount('activeAdverts as advert_count')
                    ->having('advert_count', '>', '0');
            }

            $result['subways'] = $subways->get();
        }

        if (!$geoType OR $geoType === 'administratives') {
            $administratives = Administrative::query()->select('id', 'name_ru', 'name_uk', 'translit')
                ->where('city_id', $city_id);

            if (!empty($query)) {
                $administratives->where(function ($builder) use ($query) {
                    $builder->where('name_uk', 'like', $query . '%')
                        ->orWhere('name_ru', 'like', $query . '%');
                });
            } else {
                $administratives->limit(100);
            }

            if (!$geoType) {
                $administratives->withCount('activeAdverts as advert_count')
                    ->having('advert_count', '>', '0');
            }

            $result['administratives'] = $administratives->get();
        }
        if ($geoType) {
            $result = $result[$geoType];
        }

        return response()->json($result);
    }
}

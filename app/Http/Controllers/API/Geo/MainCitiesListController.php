<?php

namespace App\Http\Controllers\API\Geo;

use App\Advert;
use App\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MainCitiesListController
{
    /**
     * @OA\Get(
     *     path="/geo/main-cities",
     *     tags={"Geo"},
     *     summary="List main cities",
     *     operationId="geo_main_cities_list",
     *     @OA\Response(
     *         response=200,
     *         description="All main cities received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                     ref="#/components/schemas/City"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=204, description="No Content")
     * )
     */

    /**
     * Get cities list
     *
     * @return JsonResponse
     */

    public function execute(): JsonResponse
    {
        $mainCitiesRaw = Cache::remember('mainCities', 60, function () {
            return DB::table('adverts')
                ->select('adverts.city_id', 'cities.uuid', DB::raw('count(city_id) as total'))
                ->where('status', 'enabled')
                ->groupBy('city_id')
                ->orderBy('total', 'DESC')
                ->leftJoin('cities', 'adverts.city_id', '=', 'cities.id')
                ->take(5)
                ->get();
        });

        $mainCities = [];
        foreach ($mainCitiesRaw as $c) {
            $city = Cache::remember('city_' . $c->city_id, 60, function () use ($c) {
                return City::find($c->city_id);
            });

            array_push($mainCities, $city);
        }

        return response()->json($mainCities);
    }
}

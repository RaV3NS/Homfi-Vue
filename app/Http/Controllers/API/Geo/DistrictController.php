<?php

namespace App\Http\Controllers\API\Geo;

use App\District;

/**
 * Class SearchController
 */
class DistrictController
{
    /**
     * @OA\Get(
     *     path="/geo/districts",
     *     tags={"Geo"},
     *     summary="Return list of districts",
     *     operationId="district_list",
     *     @OA\Response(
     *         response=200,
     *         description="Districts received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                    ref="#/components/schemas/District"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=204, description="No Content"),
     * )
     */

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function execute()
    {
        return response()->json(District::all());
    }
}

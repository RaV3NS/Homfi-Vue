<?php


namespace App\Http\Controllers\API\Advert;

use App\Option;
use Illuminate\Http\JsonResponse;

class OptionListController
{
    /**
     * @OA\Get(
     *     path="/adverts/options",
     *     tags={"Advert"},
     *     summary="List All Advert Options",
     *     operationId="advert_options_list",
     *     @OA\Response(
     *         response=200,
     *         description="All options received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Option"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=204, description="No Content")
     * )
     */

    /**
     * Get adverts list
     *
     * @return JsonResponse
     */
    public function execute(): JsonResponse
    {
        $options = Option::all();

        return response()->json($options);
    }
}

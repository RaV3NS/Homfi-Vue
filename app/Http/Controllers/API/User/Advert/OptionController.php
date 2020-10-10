<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Advert;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Advert\CreateOption;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="AdvertOptionError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="options",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class OptionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/{user_id}/adverts/{advert_id}/option",
     *     tags={"Create Advert"},
     *     summary="Store Advert Option",
     *     operationId="create_advert_option",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="advert_id",
     *         in="path",
     *         description="Advert id",
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
     *                    property="options",
     *                    type="array",
     *                    @OA\Items(type="integer")
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Advert options stored successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/PublicAdvert"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/AdvertOptionError"
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
     * Create option advert
     *
     * @param int $userId
     * @param int $advertId
     * @param CreateOption $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, int $advertId, CreateOption $request): JsonResponse
    {
        $advert = User::findOrFail($userId)->adverts()->findOrFail($advertId);

        try {
            $options = $request->get('options');
            $oldOptionIds = $advert->options->pluck('id')->toArray();
            $toAddOptions = array_diff($options, $oldOptionIds);
            $toDeleteOptions = array_diff($oldOptionIds, $options);
            foreach($toAddOptions as $option) {
                $optionModel['option_id'] = $option;
                $optionModel['advert_id'] = $advertId;

                DB::table('advert_option')->insert($optionModel);
            }

            foreach($toDeleteOptions as $option) {
                DB::table('advert_option')
                    ->where('advert_id', $advertId)
                    ->where('option_id', $option)
                    ->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Cant create advert option', 400);
        }

        return response()->json($advert);
    }
}

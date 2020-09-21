<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Option;
use App\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="UserAdvertShowError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="page",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class ShowController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/{user_id}/adverts/{advert_id}",
     *     tags={"User"},
     *     summary="Get User Advert by id",
     *     operationId="user_single_advert",
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
     *     @OA\Response(
     *         response=200,
     *         description="User advert received successfully",
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
     *                 ref="#/components/schemas/UserAdvertShowError"
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
     * @param int $userId
     * @param int $advertId
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, int $advertId): JsonResponse
    {
        try{
            $advert = User::findOrFail($userId)
                ->adverts()
                ->with(['parameters', 'options'])
                ->without('user')
                ->findOrFail($advertId);

            $advert->parameters->append('value');

            $mainCategoryOptions = Option::whereIn('key', config('settings.options_categories.main'))
                ->pluck('key')
                ->toArray();

            foreach($advert->options as $option){
                if(in_array($option->key, config('settings.options_categories.main')) AND $option->category !== 'main') {
                    $cOption = clone($option);
                    $cOption->category = 'main';
                    $advert->options->add($cOption);
                }
            }

            $advert->getImageUrls();
            unset($advert->media);

            return response()->json($advert);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Can\'t find advert', 404);
        }
    }
}

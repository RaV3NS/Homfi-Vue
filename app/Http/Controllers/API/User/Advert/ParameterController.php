<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Advert;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Advert\CreateParameter;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="AdvertParameterError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="parameters",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class ParameterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/{user_id}/adverts/{advert_id}/parameter",
     *     tags={"Create Advert"},
     *     summary="Store Advert Parameter",
     *     operationId="create_advert_parameter",
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
     *                    property="parameters",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    nullable=true,
     *                    @OA\Items(
     *                       type="object",
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer"
     *                       ),
     *                       @OA\Property(
     *                          property="key",
     *                          type="integer"
     *                       ),
     *                       @OA\Property(
     *                          property="value",
     *                          type="string"
     *                       )
     *                    )
     *                ),
     *                @OA\Property(
     *                    property="price_month",
     *                    type="integer"
     *                ),
     *                @OA\Property(
     *                    property="body",
     *                    type="string"
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Advert parameters stored successfully",
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
     *                 ref="#/components/schemas/AdvertParameterError"
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
     * Create parameter advert
     *
     * @param int $userId
     * @param int $advertId
     * @param CreateParameter $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, int $advertId, CreateParameter $request): JsonResponse
    {
        $advert = User::findOrFail($userId)->adverts()->with('parameters')->findOrFail($advertId);

        try {
            $parameters = $request->get('parameters');
            $advertParameters = [];

            foreach($parameters as $parameter) {
                $parameterModel['parameter_id'] = $parameter['id'];
                $parameterModel['advert_id'] = $advertId;
                $parameterModel['value'] = $parameter['value'];

                if(in_array($parameter['key'], Advert::$parameters)) {
                    $advertParameters[$parameter['key']] = $parameterModel['value'];
                }

                DB::table('advert_parameter')->insertOrIgnore($parameterModel);
            }

            $advertParameters['price_month'] = $request->get('price_month');
            $advertParameters['body'] = $request->get('body') ?? '';

            $advert->update($advertParameters);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Cant create advert parameter', 400);
        }

        return response()->json($advert);
    }
}

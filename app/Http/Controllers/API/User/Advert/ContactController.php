<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Advert;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Advert\Create;
use App\Http\Requests\API\User\Advert\CreateContact;
use App\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="AdvertContactError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="first_name",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="last_name",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="email",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="phones",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="social_links",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class ContactController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/{user_id}/adverts/{advert_id}/contact",
     *     tags={"Create Advert"},
     *     summary="Store Advert Contact",
     *     operationId="create_advert_contact",
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
     *                    property="first_name",
     *                    required={"true"},
     *                    type="string",
     *                    minLength=2
     *                ),
     *                @OA\Property(
     *                    property="last_name",
     *                    required={"true"},
     *                    type="string",
     *                    minLength=2
     *                ),
     *                @OA\Property(
     *                    property="email",
     *                    type="string",
     *                    format="email",
     *                    nullable=true
     *                ),
     *                @OA\Property(
     *                    property="phones",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    nullable=true,
     *                    @OA\Items(
     *                       type="object",
     *                       ref="#/components/schemas/CreatePhone"
     *                    )
     *                ),
     *                @OA\Property(
     *                    property="social_links",
     *                    type="object",
     *                    collectionFormat="multi",
     *                    nullable=true,
     *                    ref="#/components/schemas/SocialLinks"
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Advert contacts stored successfully",
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
     *                 ref="#/components/schemas/AdvertContactError"
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
     * Create contact advert
     *
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, int $advertId, CreateContact $request): JsonResponse
    {
        $advert = User::findOrFail($userId)->adverts()->findOrFail($advertId);

        try {
            $validated = $request->validated();

            foreach($validated['phones'] as $phone) {
                if($advert->phones->count()){
                    $advert->phones()->where('number', $phone['number'])->delete();
                }

                $phone['model'] = Advert::class;
                $phone['model_id'] = $advertId;
                $advert->phones()->create($phone);
            }

            unset($validated['phones']);

            $advert->update($validated);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Cant create advert', 400);
        }

        return response()->json($advert);
    }
}

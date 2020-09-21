<?php

namespace App\Http\Controllers\API\User\Advert;

use App\AdminNotification;
use App\Advert;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Advert\Update;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="UserAdvertUpdateError",
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
 *              property="city_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="social_links",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="type",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="price_month",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *      )
 *  )
 */
class UpdateController extends Controller
{
    /**
     * @OA\Put(
     *     path="/user/{user_id}/adverts/{advert_id}",
     *     tags={"User"},
     *     summary="Update User Advert by id",
     *     operationId="update_user_advert",
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
     *                    property="city_id",
     *                    type="integer"
     *                ),
     *                @OA\Property(
     *                    property="phones[]",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    nullable=true,
     *                    @OA\Items(type="integer")
     *                ),
     *                @OA\Property(
     *                    property="social_links[]",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    nullable=true,
     *                    @OA\Items(type="string")
     *                ),
     *                @OA\Property(
     *                    property="type",
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="price_month",
     *                    type="integer"
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Advert updated successfully",
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
     *                 ref="#/components/schemas/UserAdvertUpdateError"
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
     * @param Update $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, int $advertId, Update $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $advert = User::findOrFail($userId)->adverts()->with(['options'])->findOrFail($advertId);

            if($advert->editing === 1) {
                return response()->json([], 409);
            }

            if (
                $advert->status === 'draft'
                AND isset($validated['status'])
                AND $validated['status'] === 'moderate'
            ) {
                if(empty($advert->publish_date)) {
                    $advert->newAdminNotifications()->create([
                        'admin_id' => 1,
                        'advert_id' => $advert->id,
                        'type' => 'new_advert',
                        'title' => AdminNotification::getTitle(AdminNotification::TYPE_NEW_ADVERT, $advert->id),
                        'status' => 'new'
                    ]);
                } else {
                    $advert->newAdminNotifications()->create([
                        'admin_id' => 1,
                        'advert_id' => $advert->id,
                        'type' => 'advert_moderate',
                        'title' => AdminNotification::getTitle(AdminNotification::TYPE_ADVERT_MODERATE, $advert->id),
                        'status' => 'new'
                    ]);
                }
            } elseif ($advert->status != 'draft' AND empty($validated['status']) AND !empty($validated)) {
                $validated['status'] = 'draft';
            }

            if (!empty($validated['phones'])) {
                $advert->phones()->delete();
                foreach ($validated['phones'] as $phone) {
                    $phone['model'] = Advert::class;
                    $phone['model_id'] = $advertId;
                    $advert->phones()->create($phone);
                }
                unset($validated['phones']);
            }

            if($request->has('parameters')){
                $parameters = $request->get('parameters');

                foreach ($parameters as $parameter) {
                    $parameterModel['parameter_id'] = $parameter['id'];
                    $parameterModel['advert_id'] = $advertId;
                    $parameterModel['value'] = $parameter['value'];

                    if (in_array($parameter['key'], Advert::$parameters)) {
                        $validated[$parameter['key']] = $parameterModel['value'];
                    }

                    if ($parameter['value'] === null) {
                        DB::table('advert_parameter')
                            ->where('parameter_id', $parameterModel['parameter_id'])
                            ->where('advert_id', $parameterModel['advert_id'])
                            ->delete();
                    } else {
                        DB::table('advert_parameter')
                            ->updateOrInsert(
                                [
                                    'parameter_id' => $parameterModel['parameter_id'],
                                    'advert_id' => $parameterModel['advert_id']
                                ],
                                ['value' => $parameterModel['value']]
                            );
                    }
                }

                unset($validated['parameters'], $validated['options']);
            }

            if($request->has('options')) {
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

                unset($validated['options']);
            }

            $advert->update($validated);

            $advert->load('parameters', 'options');

            $advert->parameters->append('value');

            foreach ($advert->options as $option) {
                if (in_array($option->key, config('settings.options_categories.main'))
                    AND $option->category !== 'main') {
                    $cOption = clone($option);
                    $cOption->category = 'main';
                    $advert->options->add($cOption);
                }
            }

            return response()->json($advert);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Can\'t update advert', 404);
        }
    }
}

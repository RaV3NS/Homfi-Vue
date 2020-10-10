<?php

namespace App\Http\Controllers\API\Complain;

use App\AdminNotification;
use App\Advert;
use App\Complain;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Complain\CreateRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="CreateComplainError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="user_id",
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
 *              property="phone",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="reason",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="body",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *      )
 *  )
 */

class CreateController extends Controller
{
    /**
     * @OA\Post(
     *     path="/adverts/{advert_id}/complain",
     *     tags={"Advert"},
     *     summary="Create complain for advert",
     *     operationId="create_complain",
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
     *                    property="user_id",
     *                    type="integer",
     *                    example="1"
     *                ),
     *                @OA\Property(
     *                    property="email",
     *                    type="string",
     *                    example="string@string.com"
     *                ),
     *                @OA\Property(
     *                    property="phone",
     *                    type="string",
     *                    example="380501234567"
     *                ),
     *                @OA\Property(
     *                    property="reason",
     *                    type="string"
     *                ),
     *                @OA\Property(
     *                    property="body",
     *                    type="string"
     *                ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reason created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/CreateComplainError"
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
     *     )
     * )
     */

    /**
     * Create complain advert
     *
     * @param int $advertId
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $advertId, CreateRequest $request)
    {
        $validated = $request->validated();
        $validated['status'] = Complain::STATUS_PENDING;
        try {
            $advert = Advert::findOrFail($advertId);
            $validated['advert_id'] = $advert->id;

            $advert->complains()->create($validated);

            $advert->newAdminNotifications()->create([
                'admin_id' => 1,
                'advert_id' => $advert->id,
                'type' => 'complain',
                'title' => AdminNotification::getTitle(AdminNotification::TYPE_COMPLAIN, $advert->id),
                'status' => 'new'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Can\'t create complain', 400);
        }

        return response()->json();
    }
}

<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Advert\CreatePhoto;
use App\Http\Requests\API\User\Advert\UpdatePhoto;
use App\User;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="UpdateAdvertPhotoError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="photos",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class UpdatePhotoController extends Controller
{
    /**
     * @OA\Put(
     *     path="/user/{user_id}/adverts/{advert_id}/photo",
     *     tags={"Create Advert"},
     *     summary="Update Advert Photos",
     *     operationId="update_advert_photo",
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
     *                    property="photos",
     *                    type="array",
     *                    @OA\Items(
     *                        type="object",
     *                        @OA\Schema(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer"
     *                          ),
     *                          @OA\Property(
     *                              property="rotation",
     *                              type="integer",
     *                          )
     *                       )
     *                    )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="No content"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/AdvertPhotoError"
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
     * Create advert
     *
     * @param int $userId
     * @param int $advertId
     * @param UpdatePhoto $request
     * @return JsonResponse
     * @throws ApiException
     */

    public function execute(int $userId, int $advertId, UpdatePhoto $request): JsonResponse
    {
        $user = User::findOrFail($userId);
        $advert = $user->adverts()->with('media')->findOrFail($advertId);
        $validated = $request->validated();

        if ($advert->status != 'draft' AND empty($validated['status']) AND !empty($validated)) {
            $advert->update(['status' => 'draft']);
        }

        try {
            $store = [];

            foreach($validated['photos'] as $index => $photo) {
                $store['saveIds'][] = $photo['id'];
                $store['rotation'][$photo['id']] = $photo['rotation'];
                $store['index'][$photo['id']] = $index;
            }

            $images = $advert->getMedia('images');

            foreach($images as $image) {
                if(!in_array($image->id, $store['saveIds'])) {
                    $image->delete();
                } else {
                    $image->order_column = $store['index'][$image->id];

                    if(!empty($store['rotation'][$image->id])) {
                        $image->manipulations = [
                            '*' => ['orientation' => $this->calcRotation($image->getFullUrl(), $store['rotation'][$image->id])],
                        ];
                    }

                    $image->save();
                }
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Cant update advert photo', 400);
        }

        return response()->json();
    }

    protected function calcRotation($file, $rotation)
    {
        try{
            $exif = exif_read_data($file);
            $ort = isset($exif['IFD0']['Orientation']) ? $exif['IFD0']['Orientation'] : $exif['Orientation'];
            switch($ort)
            {
                case 3: // 180 rotate left
                    $rotation += 180;
                    break;

                case 6: // 90 rotate right
                    $rotation -= 90;
                    break;

                case 8:    // 90 rotate left
                    $rotation += 90;
                    break;
            }
        } catch (\Exception $e) {
            Log::error('calc photo rotation: ' . $e->getMessage());
        }


        return $rotation;
    }
}

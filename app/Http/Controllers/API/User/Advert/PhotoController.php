<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Advert\CreatePhoto;
use App\User;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *      schema="AdvertPhotoError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="photos",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="rotation",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class PhotoController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/{user_id}/adverts/{advert_id}/photo",
     *     tags={"Create Advert"},
     *     summary="Store Advert Photo",
     *     operationId="create_advert_photo",
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
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="photos[]",
     *                    type="array",
     *                    @OA\Items(
     *                        type="string",
     *                        format="binary"
     *                    )
     *                ),
     *                @OA\Property(
     *                    property="rotation[]",
     *                    type="array",
     *                    @OA\Items(
     *                        type="integer"
     *                    )
     *                )
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
     * @param CreatePhoto $request
     * @return JsonResponse
     * @throws ApiException
     */

    public function execute(int $userId, int $advertId, CreatePhoto $request): JsonResponse
    {
        $user = User::findOrFail($userId);
        $advert = $user->adverts()->with('media')->findOrFail($advertId);
        $validated = $request->validated();
        $result = [];

        try {
            $advert->addAllMediaFromRequest()->each(function ($fileAdder, $index) use ($validated, &$result) {

               if(!empty($validated['rotation'][$index])) {
                    $rotation = $this->calcRotation($validated['photos'][$index], $validated['rotation'][$index]);
                    $fileAdder->withManipulations([
                        '*' => ['orientation' => $rotation],
                    ]);
               }

               $image = $fileAdder->toMediaCollection('images');
               $result[] = [
                   'id' => $image->id,
                   'rotation' => $validated['rotation'][$index]
               ];
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Cant create advert photo', 400);
        }

        if ($advert->status != 'draft' AND empty($validated['status']) AND !empty($validated)) {
            $advert->update(['status' => 'draft']);
        }

        return response()->json($result);
    }

    protected function calcRotation($file, $rotation)
    {
        if(exif_imagetype($file) === IMAGETYPE_JPEG) {
            try {
                $exif = exif_read_data($file);
                $ort = isset($exif['IFD0']['Orientation']) ? $exif['IFD0']['Orientation'] : $exif['Orientation'];
                switch ($ort) {
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
        }

        return $rotation;
    }
}

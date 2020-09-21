<?php

namespace App\Http\Controllers\API\Content;

use App\Content;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowController extends Controller
{
    /**
     * @OA\Get(
     *     path="/contents/{content_id}",
     *     tags={"Content"},
     *     summary="Show one content",
     *     operationId="content_show",
     *     @OA\Parameter(
     *         name="content_id",
     *         in="path",
     *         description="Content id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Content received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Content"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/CommonError"
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
     *     )
     * )
     */

    /**
     * Get contents list
     *
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $id): JsonResponse
    {
        $content = Content::query()->findOrFail($id);

        return response()->json($content);
    }
}

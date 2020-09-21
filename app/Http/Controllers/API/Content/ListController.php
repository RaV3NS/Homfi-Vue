<?php

namespace App\Http\Controllers\API\Content;

use App\Content;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Content\ListRequest;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *      schema="ContentListError",
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

class ListController extends Controller
{
    /**
     * @OA\Get(
     *     path="/contents",
     *     tags={"Content"},
     *     summary="List All Content",
     *     operationId="content_list",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number with results",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Content received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="current_page",
     *                     type="integer",
     *                     format="int32"
     *                 ),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         ref="#/components/schemas/Content"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="first_page_url",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="from",
     *                     type="integer",
     *                     format="int32"
     *                 ),
     *                 @OA\Property(
     *                     property="next_page_url",
     *                     type="string",
     *                     nullable=true
     *                 ),
     *                 @OA\Property(
     *                     property="path",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="per_page",
     *                     type="integer",
     *                     format="int32"
     *                 ),
     *                 @OA\Property(
     *                     property="prev_page_url",
     *                     type="string",
     *                     nullable=true
     *                 ),
     *                 @OA\Property(
     *                     property="to",
     *                     type="integer",
     *                     format="int32"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=204, description="No Content"),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/ContentListError"
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
     * @return Paginator
     * @throws ApiException
     */
    public function execute(ListRequest $request): Paginator
    {
        return Content::query()->SimplePaginate();
    }
}

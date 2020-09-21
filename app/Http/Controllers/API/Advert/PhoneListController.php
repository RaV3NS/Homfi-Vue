<?php

namespace App\Http\Controllers\API\Advert;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Phone;
use App\User;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PhoneListController extends Controller
{
    /**
     * @OA\Post(
     *     path="/adverts/phone/{cypher}",
     *     tags={"Advert"},
     *     summary="Returns advert phones by cypher",
     *     operationId="advert_phone_list",
     *     @OA\Parameter(
     *         name="cypher",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
     *     @OA\Response(
     *         response=200,
     *         description="Advert phones received successfully",
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
     *                         ref="#/components/schemas/Phone"
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
     *                 ref="#/components/schemas/PhoneListError"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Code is expired",
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
     * Get advert phones
     *
     * @param string $cypher
     * @return JsonResponse | Paginator
     * @throws ApiException
     */
    public function execute(string $cypher)
    {
        try {
            $decrypt = decrypt($cypher);
            if ($decrypt[2] < now()->getTimestamp()) {
                return response()->json(['error' => 'Phone code timeout error'], 419);
            }
            return Phone::query()->select([
                'number',
                'is_main',
                'messengers'
            ])->where(['model_id' => $decrypt[0], 'model' => $decrypt[1]])->SimplePaginate();
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' - ' . $e->getTraceAsString());

            throw new ApiException('Cypher is invalid', 400);
        }
    }
}

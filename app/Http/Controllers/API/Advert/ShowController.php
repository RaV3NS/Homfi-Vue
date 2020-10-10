<?php

namespace App\Http\Controllers\API\Advert;

use App\Advert;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Traits\Seo;
use App\Option;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowController extends Controller
{
    use Seo;
    /**
     * @OA\Get(
     *     path="/adverts/{advert_id}",
     *     tags={"Advert"},
     *     summary="Show one advert",
     *     operationId="adverts_show",
     *     @OA\Parameter(
     *         name="advert_id",
     *         in="path",
     *         description="Advert id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Advert received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="advert",
     *                     type="object",
     *                     ref="#/components/schemas/PublicAdvert"
     *                 ),
     *                 @OA\Property(
     *                     property="seo",
     *                     type="object",
     *                     ref="#/components/schemas/SeoAdvert"
     *                 )
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
     * @param int $id
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $id): JsonResponse
    {
        try{
            $advert = Advert::query()
                ->where('status', Advert::STATUS_ENABLED)
                ->with([
                    'options',
                    'parameters',
                ])->without(['phones', 'user'])->findOrFail($id);

            $advert->parameters->append('value');

            foreach($advert->options as $option){
                if(in_array($option->key, config('settings.options_categories.main')) AND $option->category !== 'main') {
                    $cOption = clone($option);
                    $cOption->category = 'main';
                    $advert->options->add($cOption);
                }
            }

            $advert->getImageUrls();
            unset($advert->media);

            $replaces['offer_name'] = $advert->title['uk'];
            $replaces['offer_description'] = $advert->body;

            $this->setReplaces($replaces);

            $seo = $this->advertPageTemplate($advert);

            return response()->json(compact('advert', 'seo'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Can\'t find advert', 404);
        }

    }
}

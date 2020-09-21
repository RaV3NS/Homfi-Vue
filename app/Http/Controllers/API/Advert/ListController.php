<?php

namespace App\Http\Controllers\API\Advert;

use App\Advert;
use App\City;
use App\Exceptions\ApiException;
use App\Filters\AdvertFilter;
use App\Filters\GeoFilter;
use App\Filters\ParameterFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Advert\ListRequest;
use App\Traits\Seo;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * @OA\Schema(
 *      schema="AdvertsListError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="city_id",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="page",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="order",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="query",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="filter",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */
class ListController extends Controller
{
    use Seo;

    /**
     * @OA\Get(
     *     path="/adverts",
     *     tags={"Advert"},
     *     summary="List All Adverts",
     *     operationId="adverts_list",
     *     @OA\Parameter(
     *         name="city_id",
     *         in="query",
     *         description="City ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="String to filter adverts by advert fields",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *         example="{""type"":[""flat""],""price_month"":{""from"":10,""to"":1000},""room_count"":[1, 2]}"
     *     ),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search in advert body",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         example="Осокорки"
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Sorting order",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"lowest_price", "highest_price", "newest"},
     *         ),
     *         example="newest"
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
     *         description="All adverts received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="result",
     *                     type="object",
     *                      @OA\Property(
     *                          property="current_page",
     *                          type="integer",
     *                          format="int32"
     *                      ),
     *                      @OA\Property(
     *                          property="data",
     *                          type="array",
     *                          @OA\Items(
     *                               ref="#/components/schemas/PublicAdvert"
     *                          )
     *                      ),
     *                      @OA\Property(
     *                          property="first_page_url",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="from",
     *                          type="integer",
     *                          format="int32"
     *                      ),
     *                      @OA\Property(
     *                          property="next_page_url",
     *                          type="string",
     *                          nullable=true
     *                      ),
     *                      @OA\Property(
     *                          property="path",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="per_page",
     *                          type="integer",
     *                          format="int32"
     *                      ),
     *                      @OA\Property(
     *                          property="prev_page_url",
     *                          type="string",
     *                          nullable=true
     *                      ),
     *                      @OA\Property(
     *                          property="to",
     *                          type="integer",
     *                          format="int32"
     *                      ),
     *                  ),
     *                  @OA\Property(
     *                      property="seo",
     *                      type="object",
     *                      ref="#/components/schemas/SeoAdvertList"
     *                  )
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
     *                 ref="#/components/schemas/AdvertsListError"
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
     *     )
     * )
     */

    /**
     * Get adverts list
     *
     * @param ListRequest $request
     * @param AdvertFilter $advertFilter
     * @param ParameterFilter $parameterFilters
     * @param GeoFilter $geoFilter
     * @return Paginator
     */
    public function execute(ListRequest $request, AdvertFilter $advertFilter, ParameterFilter $parameterFilters, GeoFilter $geoFilter)
    {
        $validated = $request->validated();
        $adverts = Advert::query()
            ->selectRaw('adverts.*, count(adverts.id) as cnt')
            ->where('city_id', '=', $validated['city_id'])
            ->where('status', '=', Advert::STATUS_ENABLED);

        $adverts->with(['media', 'parameters']);
        $adverts->without(['user', 'phones']);
        $adverts->groupBy('adverts.id');

        $filter = [];
        if(!empty($validated['filter'])){
            $filter = json_decode($validated['filter'], true);
        }

        if (!empty($filter)) {
            $adverts->advertFilter($advertFilter);

            $adverts->geoFilter($geoFilter);

            if ($parameterFilters->countFilters()) {
                $adverts->parameterFilter($parameterFilters);
                $adverts->having('cnt', '>=', $parameterFilters->countFilters());
            }
        }

        if (!empty($validated['query'])) {
            $adverts->where('body', 'like', '%' . $validated['query'] . '%');
        }

        if (!empty($validated['order'])) {
            if ($validated['order'] === 'newest') {
                $adverts->orderBy('updated_at', 'desc');
            }

            if ($validated['order'] === 'lowest_price') {
                $adverts->orderBy('price_month', 'asc');
            }

            if ($validated['order'] === 'highest_price') {
                $adverts->orderBy('price_month', 'desc');
            }
        } else {
            $adverts->orderBy('updated_at', 'desc');
        }

        $result = $adverts->simplePaginate();
        $result->map(function ($advert) {
            $advert->getImageUrls();
            $advert->parameters->append('value');
            unset($advert->media);
        });

        $replaces['city'] = City::find($request->get('city_id'))->name;
        $replaces['count'] = $result->count();
        $this->setReplaces($replaces);
        $this->setFilters($filter);

        if (empty($filter)) {
            $seo = $this->advertCityTemplate();
        } else {
            $seo = $this->advertFilterTemplate();
        }

        return compact('result', 'seo');
    }
}

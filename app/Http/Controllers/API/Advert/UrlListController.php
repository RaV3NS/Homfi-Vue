<?php

namespace App\Http\Controllers\API\Advert;

use App\Advert;
use App\City;
use App\Exceptions\ApiException;
use App\Filters\AdvertFilter;
use App\Filters\GeoFilter;
use App\Filters\OptionFilter;
use App\Filters\ParameterFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Advert\ListRequest;
use App\Http\Requests\API\Advert\UrlListRequest;
use App\Traits\Seo;
use App\Traits\AdvertListUrlParser;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * @OA\Schema(
 *      schema="AdvertsUrlListError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="url",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */
class UrlListController extends Controller
{
    use AdvertListUrlParser, Seo;

    /**
     * @OA\Get(
     *     path="/adverts-url",
     *     tags={"Advert"},
     *     summary="List All Adverts through url",
     *     operationId="adverts_list_url",
     *     @OA\Parameter(
     *         name="url",
     *         in="query",
     *         description="URL with parameters",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         example="http://restate.loc/ru/kiev-odnokomnatnyie,dvuhkomnatnyie-kvartiry,doma-goloseevskij_rajon?pricemonth_min=1000&pricemonth_max=5000"
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
     *                 ref="#/components/schemas/AdvertsUrlListError"
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
     * @param UrlListRequest $request
     * @param AdvertFilter $advertFilters
     * @param ParameterFilter $parameterFilters
     * @param OptionFilter $optionFilter
     * @param GeoFilter $geoFilter
     * @return Paginator
     */
    public function execute(UrlListRequest $request, AdvertFilter $advertFilters, ParameterFilter $parameterFilters, OptionFilter $optionFilter, GeoFilter $geoFilter)
    {
        $url = $request->get('url');

        $this->request = $request;
        $this->processUrl($url);

        if(empty($this->city)){
            return response()->json();
        }

        $adverts = Advert::query()
            ->selectRaw('adverts.*')
            ->where('city_id', '=', $this->city->id)
            ->where('status', '=', Advert::STATUS_ENABLED);

        $adverts->with(['media', 'parameters', 'options', 'administrative', 'city.region', 'city.region.district', 'subway']);
        $adverts->without(['user', 'phones']);
        $adverts->groupBy('adverts.id');

        if (!empty($this->advertFilter)) {
            $advertFilters->setFilter($this->advertFilter);
            $adverts->advertFilter($advertFilters);
        }

        if (!empty($this->geoFilter)) {
            $geoFilter->setFilter($this->geoFilter);
            $adverts->geoFilter($geoFilter);
        }

        if (!empty($this->optionFilter)) {
            $optionFilter->setFilter($this->optionFilter);
            $adverts->optionFilter($optionFilter);
        }

        if (!empty($this->paramFilter)) {
            $parameterFilters->setFilter($this->paramFilter);
            if ($parameterFilters->countFilters()) {
                $adverts->parameterFilter($parameterFilters);
                //$parameterFilters->hasNoValue([5,6]);
                //$adverts->having('cnt', '>=', $parameterFilters->countFilters());
            }
        }

        if (!empty($this->query_body)) {
            $adverts->where('body', 'like', '%' . urldecode($this->query_body) . '%');
        }

        if (!empty($this->order)) {
            if ($this->order === 'newest') {
                $adverts->orderBy('updated_at', 'desc');
            }

            if ($this->order === 'lowest_price') {
                $adverts->orderBy('price_month', 'asc');
            }

            if ($this->order === 'highest_price') {
                $adverts->orderBy('price_month', 'desc');
            }
        } else {
            $adverts->orderBy('updated_at', 'desc');
        }

        $result = $adverts->paginate(null, ['*'], 'page', $this->getPage());
        $result->map(function ($advert) {
            $advert->parameters->append('value');
            $advert->getImageUrls();

            unset($advert->media);
        });

        $replaces['city'] = $this->city->name;
        $replaces['count'] = $result->total();
        $this->setReplaces($replaces);

        $filter = $this->getTotalFilter();

        $filter['city'] = $this->city;
        $filter['geoObject'] = $this->geoObject;

        $this->setFilters($filter);

        if (empty($filter)) {
            $seo = $this->advertCityTemplate();
        } else {
            $seo = $this->advertFilterTemplate();
        }

        return compact('result', 'seo', 'filter');
    }

    public function getTotalFilter()
    {
        return array_merge($this->advertFilter, $this->paramFilter, $this->geoFilter, $this->optionFilter, ['query' => $this->query_body]);
    }
}

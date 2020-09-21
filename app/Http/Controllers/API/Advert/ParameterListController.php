<?php


namespace App\Http\Controllers\API\Advert;

use App\Http\Requests\API\Advert\ListParameterRequest;
use App\Parameter;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 *      schema="ParameterListError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="filter",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *     )
 * )
 */

class ParameterListController
{
    /**
     * @OA\Get(
     *     path="/adverts/parameters",
     *     tags={"Advert"},
     *     summary="List All Advert Parameters",
     *     operationId="advert_parameters_list",
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="If equals 'full' returns with additional parameters",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         example="full"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="All parameters received successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Parameter"
     *                 )
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
     *                 ref="#/components/schemas/ParameterListError"
     *             )
     *         )
     *     )
     * )
     */

    /**
     * Get parameters list
     *
     * @param ListParameterRequest $request
     * @return JsonResponse
     */
    public function execute(ListParameterRequest $request): JsonResponse
    {
        $parameters = Parameter::all()->toArray();

        if($request->get('filter') === 'full') {
            $additional_parameters = [];

            foreach(config('settings.additional_filter_parameters') as $param) {
                $parameter = [];
                $allowed_values = [];

                $parameter['key'] = $param;

                if(is_array(trans('parameter_values.' . $parameter['key']))) {
                    foreach(trans('parameter_values.' . $parameter['key']) as $key=>$value) {
                        $allowed_value['key'] = $key;
                        $allowed_value['value_uk'] = $value;
                        $allowed_value['value_ru'] = trans('parameter_values.' . $parameter['key'] . '.' . $key, [], 'ru');

                        $allowed_values[] = $allowed_value;
                    }
                }

                $parameter['allowed_values'] = $allowed_values;

                if(!empty($allowed_values)) {
                    $parameter['type'] = 'select';
                } else {
                    $parameter['type'] = 'checkbox';
                }

                $parameter['name_ru'] =  trans('parameters.' . $parameter['key'], [], 'ru');
                $parameter['name_uk'] =  trans('parameters.' . $parameter['key']);

                if($param === 'query') {
                    $parameter['type'] = 'input';
                }

                $additional_parameters[] = $parameter;
            }

            return response()->json(array_merge($parameters, $additional_parameters));
        }


        return response()->json($parameters);
    }
}

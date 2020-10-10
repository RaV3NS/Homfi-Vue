<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Advert;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\Advert\CreateAdvert;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateAdvertController extends Controller
{

    /**
     * Create advert
     *
     * @param int $userId
     * @param Create $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function execute(int $userId, CreateAdvert $request): JsonResponse
    {
        $user = User::findOrFail($userId);

        try {
            $advert = $request->validated();

            $advert['first_name'] = $user->first_name;
            $advert['last_name'] = $user->last_name;
            $advert['email'] = $user->email;
            $advert['status'] = Advert::STATUS_DRAFT;

            if(empty($advert['lng']) OR empty($advert['lat'])) {
                $advert['lat'] = 0;
                $advert['lng'] = 0;
            }

            $advert['body'] = $advert['social_links'] = '';
            $advert['room_count'] = 0;

            unset($advert['district_id']);

            if ($advertId = $request->post('advert_id'))
                $advert = User::findOrFail($userId)->adverts()->findOrFail($advertId);
            else
                $advert = $user->adverts()->create($advert);

            // Parameters

            $parameters = $request->post('parameters');
            $advertParameters = [];

            foreach($parameters as $parameter) {

                $parameterModel['parameter_id'] = $parameter['id'];
                $parameterModel['advert_id'] = $advert->id;
                $parameterModel['value'] = $parameter['value'];

                if(in_array($parameter['key'], Advert::$parameters)) {
                    $advertParameters[$parameter['key']] = $parameterModel['value'];
                }

                DB::table('advert_parameter')->insertOrIgnore($parameterModel);
            }

            $advertParameters['price_month'] = $request->get('price_month') ?? 0;
            $advertParameters['body'] = $request->get('body') ?? '';

            $advert->update($advertParameters);

            // Options

            $options = $request->post('options');

            foreach($options as $option) {
                $optionModel['option_id'] = $option;
                $optionModel['advert_id'] = $advert->id;
                DB::table('advert_option')->insertOrIgnore($optionModel);
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ApiException('Cant create advert', 400);
        }

        return response()->json($advert);
    }

}

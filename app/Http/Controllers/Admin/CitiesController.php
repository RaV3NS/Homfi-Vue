<?php

namespace App\Http\Controllers\Admin;

use App\Administrative;
use App\City;
use App\Http\Controllers\Controller;
use App\Street;
use App\Subway;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function searchAjax(Request $request)
    {
        $cities = City::query()->with(['region', 'region.district'])->limit(20);
        if ($request->has('q')) {
            $citiesCollection = (clone $cities)->where(
                'name_uk',
                'like',
                $request->query('q').'%'
            )->get();
            $citiesRuCollection = $cities->where(
                'name_ru',
                'like',
                $request->query('q').'%'
            )->get();
            $citiesCollection = $citiesCollection->merge($citiesRuCollection);
        }

        $cities = $citiesCollection ?? $cities->get();

        $cities->each(function (City $city) {
            $city->setAppends(['full_city_name_uk', 'full_city_name_ru']);
            $city->setVisible(['full_city_name_uk', 'full_city_name_ru', 'id']);
        });

        return response()->json(['results' => $cities], 200);
    }

    public function searchStreetAjax(Request $request)
    {
        $streets = Street::query()
            ->where('city_id', $request->city_id)
            ->limit(20);
        if ($request->has('q')) {
            $streetsCollection = (clone $streets)->where(
                'name_uk',
                'like',
                $request->query('q').'%'
            )->get();
            $streetsRuCollection = $streets->where(
                'name_ru',
                'like',
                $request->query('q').'%'
            )->get();
            $streetsCollection = $streetsCollection->merge($streetsRuCollection);
        }

        $streets = $streetsCollection ?? $streets->get();

        return response()->json(['results' => $streets], 200);
    }

    public function searchAdministrativeAjax(Request $request)
    {
        $administratives = Administrative::query()
            ->where('city_id', $request->city_id)
            ->limit(20);
        if ($request->has('q')) {
            $administrativeCollection = (clone $administratives)->where(
                'name_uk',
                'like',
                $request->query('q').'%'
            )->get();
            $administrativeRuCollection = $administratives->where(
                'name_ru',
                'like',
                $request->query('q').'%'
            )->get();
            $administrativeCollection = $administrativeCollection->merge($administrativeRuCollection);
        }

        $administratives = $administrativeCollection ?? $administratives->get();

        return response()->json(['results' => $administratives], 200);
    }

    public function searchSubwayAjax(Request $request)
    {
        $subways = Subway::query()
            ->where('city_id', $request->city_id)
            ->limit(20);
        if ($request->has('q')) {
            $subwayCollection = (clone $subways)->where(
                'name_uk',
                'like',
                $request->query('q').'%'
            )->get();
            $subwayRuCollection = $subways->where(
                'name_ru',
                'like',
                $request->query('q').'%'
            )->get();
            $subwayCollection = $subwayCollection->merge($subwayRuCollection);
        }

        $subways = $subwayCollection ?? $subways->get();

        return response()->json(['results' => $subways], 200);
    }
}

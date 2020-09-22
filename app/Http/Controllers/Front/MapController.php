<?php

namespace App\Http\Controllers\Front;

use App\Advert;
use App\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request) {
        $city = City::where('translit', $request->route('city'))->first();
        return view('map', ['city' => $city]);
    }

    public function viewAdvert(Request $request) {
        return view('advert', ['id' => $request->route('advertId')]);
    }
}

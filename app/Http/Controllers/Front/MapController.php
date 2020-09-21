<?php

namespace App\Http\Controllers\Front;

use App\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request) {
        $city = City::where('translit', $request->route('city'))->first();
        if (!$city) return redirect('/kiev');
        return view('map', ['city' => $city]);
    }
}

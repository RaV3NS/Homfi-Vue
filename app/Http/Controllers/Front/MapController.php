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

    public function createAdvert() {
        return view('create-advert');
    }

    public function editAdvert(Request $request) {
        $advert = Advert::findOrFail($request->advertId);
        return view('edit-advert', ['id' => $advert->id]);
    }

    public function preview(Request $request) {
        return view('preview', ['id' => $request->route('advertId')]);
    }

    public function profile(Request $request) {
        return view('profile');
    }

    public function main() {
        return view('main');
    }
}

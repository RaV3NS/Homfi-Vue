<?php

namespace App\Http\Controllers\API\Advert;

use App\Advert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddViewController extends Controller
{
    public function execute(Request $request) {
        $advert = Advert::findOrFail($request->route('advert'));
        $advert->update(['views' => $advert->views + 1]);

        return response()->json([]);
    }
}

<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Advert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LastAdvertController extends Controller
{
    public function execute() {
        $user = Auth::user();
        try {
            $advert = $user->adverts()->where('status', 'draft')->orderBy('id', 'desc')->first();

            if ($advert)
                return response()->json(['advertId' => $advert->id]);
            else
                return response()->json(['advertId' => null]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to fetch last advert']);
        }
    }
}

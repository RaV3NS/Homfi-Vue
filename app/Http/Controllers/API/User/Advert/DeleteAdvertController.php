<?php

namespace App\Http\Controllers\API\User\Advert;

use App\Advert;
use App\Favorite;
use App\Complain;
use App\Http\Controllers\Controller;
use App\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeleteAdvertController extends Controller
{
    /**
     * @param int $userId
     * @param int $advertId
     * @return JsonResponse
     */
    public function execute(int $userId, int $advertId): JsonResponse {
        if ($userId === Auth::user()->id) {
            $advert = Advert::findOrFail($advertId);
            $media = $advert->media()->get(); // получение медиафайлов

            UserNotification::where('advert_id', $advert->id)->delete(); // удаление нотификаций
            Favorite::where('advert_id', $advert->id)->delete(); // удаление из избранного
            Complain::where('advert_id', $advert->id)->delete(); // удаление из complain

            foreach ($media as $item) {
                $advert->deleteMedia($item->id); // удаляем связанные медиафайлы
            }

            $advert->delete();
        }

        return response()->json();
    }
}

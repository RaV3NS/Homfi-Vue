<?php

namespace App\Http\Controllers\Admin;

use App\Advert;
use App\City;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Jobs\GetCoordinates;
use App\OsmQueue;
use App\Phone;
use App\Street;
use App\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ParseController extends Controller
{
    private function getUSDRate() {
        $rates = Cache::remember('rates', 120, function () {
            return file_get_contents('http://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11');
        });
        return json_decode($rates)[0]->sale; // return usd sale rate
    }

    private function getPrice($raw) {
        $parsed = filter_var( $raw, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

        if (strpos($raw, '$'))
            return floor($this->getUSDRate() * intval($parsed));

        return intval($parsed);
    }

    private function getCity($raw) {
        $city_name = explode(',', $raw)[0];
        return City::where('name_ru', $city_name)->orWhere('name_uk', $city_name)->first();
    }

    public function preview(Request $request) {
        $crawler = new Crawler(file_get_contents($request->url));

        $advert['title'] = $crawler->filter('h1')->text();
        $advert['price'] = $this->getPrice($crawler->filter('.pricelabel__value')->text());
        $advert['gallery'] = $crawler->filter('#descGallery')->children()->each(function(Crawler $el) {
            return $el->filter('a')->attr('href');
        });
        $advert['about'] = $crawler->filter('#textContent')->text();
        $advert['city'] = $this->getCity($crawler->filter('address')->children()->first()->text());
        $advert['name'] = $crawler->filter(".offer-user__actions")->children()->children()->first()->text();

        return view('admin.parse.index', ['advert' => $advert]);
    }

    public function store(Request $request) {

        $advert = new Advert();
        $user = new User();
        $phone = new Phone();

        // Create User
        $user->first_name = $request->post('name');
        $user->email = Str::random(10) . '@gmail.com';
        $user->status = 'active';
        $user->password = bcrypt(Str::random(10));
        $user->save();

        // Get City
        $city = City::find($request->post('city'));
        $city_slug = $city ? $city->translit : 'kiev';

        // Get Address
        $street = $request->post('street');
        $address = $request->post('address');

        try {
            // Get OSM coordinates for map
            $uuid = Str::uuid();
            GetCoordinates::dispatch($city->name_uk, $street, $address, $uuid,
                GetCoordinates::PHASE_WITH_LETTER, '', app()->getLocale())->onQueue('coordinates');

            $i = 40;
            while ($i > 0) {
                $osmQueue = OsmQueue::query()->firstWhere('uuid', '=', $uuid);
                if ($osmQueue) {
                    break;
                } else {
                    $i--;
                    usleep(250000);
                }
            }

            if (isset($osmQueue) && $osmQueue) {
                $osmQueue->delete();
            }

        } catch (\Exception $e) {
            var_dump($e);
        }

        // Create Advert
        $advert->title = $request->post('title');
        $advert->body = $request->post('about');
        $advert->first_name = $request->post('name');
        $advert->city_id = $request->post('city');
        $advert->user_id = $user->id;
        $advert->status = Advert::STATUS_ACCEPTED;
        $advert->price_month = $request->post('price');
        $advert->social_links = "{}";
        $advert->editing = 0;
        $advert->room_count = $request->post('room_count');
        $advert->lat = $osmQueue->lat;
        $advert->lng = $osmQueue->lng;
        $advert->save();

        // Create new phone model
        $phone->model = 'App\Advert';
        $phone->model_id = $advert->id;
        $phone->number = $request->post('phone');
        $phone->is_main = 1;
        $phone->messengers = '["viber"]';
        $phone->save();

        // Upload files and attach media to advert
        $gallery = explode('/--/', $request->post('gallery'));
        foreach ($gallery as $key => $img) {
            $url = $img;
            $contents = file_get_contents($url);
            $name = md5($url) . '.jpeg';

            Storage::put('\public/temp/'.$name, $contents);
            $file = new File(storage_path('app/public/temp/').$name);
            $advert->addMedia($file)->toMediaCollection('images');
            Storage::delete('/public/temp/'.$name);
        }

        // redirect to advert
        $url = env('APP_FRONT_URL').'/'.$city_slug.'/'.$advert->id;

        return view('admin.parse.completed', ['url' => $url]);
    }

    public function index() {
        return view('admin.parse.index');
    }
}

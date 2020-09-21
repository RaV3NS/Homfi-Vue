<?php

use App\Advert;
use App\Phone;
use App\User;
use Illuminate\Database\Seeder;

class AdvertImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Advert::where('id', '<', 11)->get()->each(function (Advert $advert){
            $images = [
                'https://r-cf.bstatic.com/images/hotel/max1024x768/162/162182704.jpg',
                'https://q-cf.bstatic.com/images/hotel/max1024x768/112/112921646.jpg',
                'https://r-cf.bstatic.com/images/hotel/max1024x768/112/112921654.jpg'
            ];

            $advert->addMediaFromUrl(array_rand($images))->toMediaCollection('images');
        });
    }
}

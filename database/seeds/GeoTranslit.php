<?php

use App\Administrative;
use App\City;
use App\Street;
use App\Subway;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GeoTranslit extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::withTrashed()->each(function ($city) {
            $city->translit = Str::slug($city->name_ru, '_', 'ru');
            $city->save();
        });

        Administrative::withTrashed()->each(function ($administrative) {
            $administrative->translit = Str::slug($administrative->name_ru, '_', 'ru');
            $administrative->save();
        });

        Street::withTrashed()->each(function ($street) {
            $street->translit = Str::slug($street->name_ru, '_', 'ru');
            $street->save();
        });

        Subway::all()->each(function ($subway) {
            $subway->translit = Str::slug($subway->name_ru, '_', 'ru');
            $subway->save();
        });
    }
}

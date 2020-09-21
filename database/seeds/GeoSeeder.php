<?php

use App\City;
use App\District;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class GeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = new Faker('uk_UA');
        $geoList = new RegionCityList($faker);

        foreach($geoList->getRegions() as $regionName){
            factory(District::class)->create([
                'name' => $regionName
            ]);
        }

        foreach($geoList->getCities() as $cityName){
            $districtName = $geoList->getRegionByCity($cityName);
            $district_id = District::query()->where('name', '=', $districtName)->first()->id;

            factory(City::class)->create([
                'name' => $cityName,
                'district_id' => $district_id
            ]);
        }
    }
}




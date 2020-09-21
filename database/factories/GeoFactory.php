<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\City;
use App\District;
use Faker\Generator as Faker;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$faker = new Faker('uk_UA');
$fadress = new RegionCityList($faker);

$factory->define(District::class, function () use ($fadress, $faker) {
    return [
        'name' => $fadress->region(),
        'name_ru' => $fadress->region(),
        'uuid' => $faker->uuid
    ];
});

$factory->define(City::class, function () use ($fadress, $faker) {
    return [
        'name' => $fadress->city(),
        'name_ru' => $fadress->city(),
        'district_id' => function(){
            return District::query()->inRandomOrder()->first()->id;
        },
        'uuid' => $faker->uuid
    ];
});


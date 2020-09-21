<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Option;
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
$faker = new Faker();

$factory->define(Option::class, function (Faker $faker) {

    return [
        'name_ru' => $faker->randomElement(trans('options')),
        'name_uk' => $faker->randomElement(trans('options')),
        'key' => array_rand(trans('options'))
        ,
    ];
});

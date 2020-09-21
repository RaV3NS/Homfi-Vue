<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Parameter;
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

$factory->define(Parameter::class, function (Faker $faker) {
    $type = $faker->randomElement(Parameter::$types);

    $parameterValues = trans('parameter_values');
    $parameterValuesKeys = array_keys($parameterValues);

    $key = array_rand(trans('parameters'));

    $allowed_values = '';

    if(in_array($key, $parameterValuesKeys)) {
        $type = 'select';
        $allowed_values = array_keys($parameterValues[$key]);
    }

    return [
        'key' => $key,
        'name_ru' => $faker->randomElement(trans('parameters')),
        'name_uk' => $faker->randomElement(trans('parameters')),
        'type' => $type,
        'allowed_values' => $allowed_values,
        'is_required' => $faker->numberBetween(0, 1),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Phone;
use Faker\Generator as Faker;
use Faker\Provider\uk_UA\PhoneNumber;

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
$faker->addProvider(new PhoneNumber($faker));

$factory->define(Phone::class, function () use ($faker) {
    $models = ['App\User', 'App\Advert'];

    $model = $models[rand(0, 1)];
    $model_id = function () use ($model) {
        return $model::query()->inRandomOrder()->first()->id;
    };

    return [
        'model' => $model,
        'model_id' => $model_id,
        'number' => '380' . $faker->numerify('#########'),
        'is_main' => $faker->numberBetween(0, 1),
        'messengers' => $faker->randomElements(config('settings.messengers'))
    ];
});

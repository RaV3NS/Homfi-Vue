<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Advert;
use App\Favorite;
use App\User;
use Faker\Generator as Faker;
use Faker\Provider\ru_RU\Text;

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
$faker = new Faker('ru_RU');
$faker->addProvider(new Text($faker));
$factory->define(Favorite::class, function () use ($faker) {
    $advert = Advert::query()->inRandomOrder()->first();
    $user_id = User::query()->inRandomOrder()->first()->id;

    return [
        'user_id' => $user_id,
        'advert_id' => $advert->id
    ];
});


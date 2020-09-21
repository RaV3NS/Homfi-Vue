<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Advert;
use App\AdvertImage;
use App\City;
use App\Street;
use App\User;
use Faker\Generator as Faker;
use Faker\Provider\ru_RU\Text;
use Faker\Provider\uk_UA\Address;
use Illuminate\Support\Str;

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
$faker->addProvider(new Address($faker));
$factory->define(Advert::class, function () use ($faker) {
    $user = User::query()->inRandomOrder()->first();
    return [
        'user_id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'type' => $faker->randomElement(Advert::$types),
        'title' => $faker->realText(30),
        'body' => $faker->realText(150),
        'status' => $faker->randomElement([Advert::STATUS_ENABLED, Advert::STATUS_MODERATE, Advert::STATUS_DISABLED, Advert::STATUS_DRAFT]),
        'city_id' => function() {
            return City::query()->inRandomOrder()->first()->id;
        },
        'street_id' => function() {
            return Street::query()->inRandomOrder()->first()->id;
        },
        'social_links' => $user->social_links,
        'show_contacts' => $faker->numberBetween(0, 1),
        'price_month' => $faker->randomDigitNotNull * 1000,
        'room_count' => $faker->numberBetween(1, 4),
        'address' => $faker->streetAddress,
        'lat' => $faker->latitude(48.38, 48.55),
        'lng' => $faker->longitude(34, 35),
    ];
});

$factory->define(AdvertImage::class, function (Faker $faker) {
    $advertId = Advert::query()->first()->id;
    return [
        'advert_id' => $advertId,
        'filename' => 'storage/app/public/images/' . $advertId . Str::random(10) . '.jpg',
        'original' => Str::random(10) . '.jpg',
        'path' => 'images/' . $advertId
    ];
});

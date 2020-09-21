<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AdminUser;
use App\Advert;
use App\Complain;
use App\ComplainLog;
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
$factory->define(Complain::class, function () use ($faker) {
    $advert = Advert::query()->inRandomOrder()->first();
    $user = User::query()->inRandomOrder()->first();

    return [
        'advert_id' => $advert->id,
        'user_id' => $user->id,
        'email' => $user->email,
        'phone' => isset($user->phones()->first()->number) ? $user->phones()->first()->number : '',
        'reason' => $faker->realText(30),
        'body' => $faker->realText(150),
        'status' => $faker->randomElement([Complain::STATUS_PENDING, Complain::STATUS_REJECTED, Complain::STATUS_SOLVED]),
    ];
});

$factory->define(ComplainLog::class, function (Faker $faker) {
    $admin_id = AdminUser::query()->inRandomOrder()->first()->id;
    $complainId = Complain::query()->inRandomOrder()->first()->id;
    return [
        'complain_id' => $complainId,
        'admin_id' => $admin_id,
        'status' => $faker->randomElement([Complain::STATUS_PENDING, Complain::STATUS_SOLVED, Complain::STATUS_REJECTED]),
        'body' => $faker->realText(120)
    ];
});

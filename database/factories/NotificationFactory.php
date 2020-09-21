<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AdminNotification;
use App\AdminUser;
use App\Advert;
use App\Complain;
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
$factory->define(AdminNotification::class, function () use ($faker) {
    $advert = Advert::query()->inRandomOrder()->first();
    $admin_id = AdminUser::query()->inRandomOrder()->first()->id;
    $statuses = [AdminNotification::STATUS_NEW, AdminNotification::STATUS_READ];
    $types = [AdminNotification::TYPE_NEW_ADVERT, AdminNotification::TYPE_COMPLAIN, AdminNotification::TYPE_ADVERT_MODERATE];

    $type = $faker->randomElement($types);
    $title = AdminNotification::getTitle($type, $advert->id);

    return [
        'title' => $title,
        'type' => $type,
        'status' => $faker->randomElement($statuses),
        'admin_id' => $admin_id,
        'advert_id' => $advert->id
    ];
});


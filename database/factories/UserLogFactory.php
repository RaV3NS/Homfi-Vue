<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AdminUser;
use App\User;
use App\UserLog;
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
$factory->define(UserLog::class, function () use ($faker) {
    $user = factory(User::class)->create();
    return [
        'user_id' => $user->id,
        'admin_id' => function() {
            return AdminUser::query()->first()->id;
        },
        'type' => $faker->randomElement([User::STATUS_ACTIVE, User::STATUS_BLOCKED, User::STATUS_DISABLED, User::STATUS_DRAFT]),
        'title' => $faker->realText(30),
        'body' => $faker->realText(120)
    ];
});

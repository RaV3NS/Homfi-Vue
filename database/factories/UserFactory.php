<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Factory as FakerFactory;
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
$faker = FakerFactory::create('ru_RU');
$faker->addProvider(new Faker\Provider\ru_RU\Person($faker));
$factory->define(User::class, function () use ($faker) {

    $socialLinks = new stdClass();
    $uid = Str::random(7);
    $socialLinks->instagram = 'https://instagram.com/' . $uid;
    $socialLinks->facebook = 'https://facebook.com/' . $uid;
    $socialLinks->facebook_messenger = array_rand(['disabled', 'enabled']);
    $socialLinks->email = $faker->email;
    $socialLinks->skype = $uid;

    $gender = $faker->randomElement(['male', 'female']);
    return [
        'first_name' => $faker->firstName($gender),
        'last_name' => $faker->lastName($gender),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'status' => 'active',
        'remember_token' => Str::random(10),
        'social_links' => $socialLinks,
        'last_login' => $faker->dateTimeBetween('-30 days', 'now')
    ];
});

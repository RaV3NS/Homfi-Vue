<?php

use App\AdminUser;
use App\Advert;
use App\Phone;
use App\User;
use App\UserLog;
use Illuminate\Database\Seeder;

class PhonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user){
                factory(Phone::class, rand(1, 3))->create([
                    'model' => User::class,
                    'model_id' => $user->id
                ]);
            });
        Advert::all()->each(function (Advert $advert){
                factory(Phone::class, rand(1, 3))->create([
                    'model' => Advert::class,
                    'model_id' => $advert->id
                ]);
            });
    }
}

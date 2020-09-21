<?php

use App\Advert;
use App\Phone;
use App\User;
use Illuminate\Database\Seeder;

class AdvertsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user){
            factory(Advert::class, rand(0, 5))->create([
                'user_id' => $user->id,
            ]);
            $user->refresh()->adverts()->each(function ($advert) {
//                factory(Phone::class, rand(1, 2))->create([
//                    'model' => Advert::class,
//                    'model_id' => $advert->id
//                ]);
            });
        });
    }
}

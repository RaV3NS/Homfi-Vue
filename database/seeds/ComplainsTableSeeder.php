<?php

use App\Advert;
use App\Complain;
use App\ComplainLog;
use App\User;
use Illuminate\Database\Seeder;

class ComplainsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Advert::all()->each(function (Advert $advert){
            if(rand(0,1)) {
                factory(Complain::class, rand(0, 2))->create([
                    'user_id' => function(){
                        return User::query()->inRandomOrder()->first()->id;
                    },
                    'advert_id' => $advert->id
                ]);

            }
        });

        Complain::all()->each(function (Complain $complain) {
            factory(ComplainLog::class, rand(0, 1))->create([
                'complain_id' => $complain->id
            ]);
        });
    }
}

<?php

use App\AdminNotification;
use App\Advert;
use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
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
                factory(AdminNotification::class, rand(0, 1))->create();
            }
        });
    }
}

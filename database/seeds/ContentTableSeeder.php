<?php

use App\Advert;
use App\Complain;
use App\ComplainLog;
use App\Content;
use App\User;
use Illuminate\Database\Seeder;

class ContentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Content::firstOrCreate([
            'body_ru'=>'Контент ru',
            'body_ua'=>'Контент ua',
        ])->save();


    }
}

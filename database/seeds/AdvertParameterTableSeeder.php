<?php

use App\Advert;
use App\Option;
use App\Parameter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvertParameterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Advert::all()->each(function (Advert $advert) {
            Parameter::all()->each(function(Parameter $parameter) use ($advert) {
                $value = '';
                if(in_array($parameter->key, ['total_space', 'living_space', 'kitchen_space'])) {
                    $value = rand(20, 120);
                }
                if($parameter->type == 'select') {
                    $value = json_decode($parameter->getAttributes()['allowed_values'], true)[array_rand($parameter->allowed_values)];
                }

                DB::table('advert_parameter')->insert(['advert_id' => $advert->id, 'parameter_id' => $parameter->id, 'value' => $value]);
            });

            Option::all()->each(function(Option $option) use ($advert) {
                if(rand(0, 4)) {
                    DB::table('advert_option')->insert(['advert_id' => $advert->id, 'option_id' => $option->id]);
                }
            });
        });


    }
}

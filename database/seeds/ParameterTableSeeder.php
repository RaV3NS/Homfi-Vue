<?php

use App\Option;
use App\Parameter;
use Illuminate\Database\Seeder;

class ParameterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(trans('parameters') as $key=>$parameter) {
            $parameterValues = trans('parameter_values');
            $parameterValuesKeys = array_keys($parameterValues);
            $result = [
                'key' => $key,
                'type' => 'range',
                'name_uk' => trans('parameters.' . $key, [], 'uk'),
                'name_ru' => trans('parameters.' . $key, [], 'ru'),
                'allowed_values' => ''
            ];
            if(in_array($key, $parameterValuesKeys)) {
                $result['allowed_values'] = array_keys($parameterValues[$key]);
                $result['type'] = 'select';
            }

            factory(Parameter::class)->create($result);
        }

        foreach(trans('options') as $key=>$parameter) {
            $categories = config('settings.options_categories');
            foreach($categories as $category_key=>$option_keys) {
                if(in_array($key, $option_keys)) {
                    $category = $category_key;
                }
            }
            factory(Option::class)->create([
                'key' => $key,
                'name_uk' => trans('options.' . $key, [], 'uk'),
                'name_ru' => trans('options.' . $key, [], 'ru'),
                'category' => $category
            ]);
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCitiesDistrictIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['district_id']);
            $blueprint->dropColumn('district_id');
            $blueprint->unsignedBigInteger('region_id');
        });

       // \Illuminate\Support\Facades\Artisan::call('update:streets --no-download --part-streets');
       // $region = \App\Region::query()->create(['name' => 'test', 'name_ru' => 'test', 'uuid' => 'test', 'district_id' => \App\District::query()->withTrashed()->first()->id]);
       // \App\City::query()->update(['region_id' => $region->id]);

        Schema::table('cities', function (Blueprint $blueprint) {
            $blueprint->foreign('region_id')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['region_id']);
            $blueprint->dropColumn('region_id');
            $blueprint->unsignedBigInteger('district_id');
        });

        \App\City::query()->update(['district_id' => \App\District::query()->withTrashed()->first()->id]);

        Schema::table('cities', function (Blueprint $blueprint) {
            $blueprint->foreign('district_id')->references('id')->on('districts');
        });
    }
}

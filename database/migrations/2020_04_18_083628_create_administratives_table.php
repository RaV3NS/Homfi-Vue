<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministrativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrative', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('osm_id')->nullable();
            $table->string('name_ru')->nullable();
            $table->string('name_uk')->nullable();
            $table->string('old_name_ru')->nullable();
            $table->string('old_name_uk')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('administrative', function (Blueprint $blueprint) {
            $blueprint->foreign('city_id')->references('id')->on('cities');
        });

        \Illuminate\Support\Facades\Artisan::call('db:seed --class=AdministrativeSeeder');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrative');
    }
}

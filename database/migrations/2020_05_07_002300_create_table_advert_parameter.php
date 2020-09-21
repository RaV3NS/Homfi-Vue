<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAdvertParameter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_parameter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id');
            $table->foreignId('parameter_id');
            $table->string('value');
        });

        Schema::table('advert_parameter', function (Blueprint $table) {
            $table->foreign('advert_id')->references('id')->on('adverts');
            $table->foreign('parameter_id')->references('id')->on('parameters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advert_parameter');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAdvertOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_option', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id');
            $table->foreignId('option_id');
        });

        Schema::table('advert_option', function (Blueprint $table) {
            $table->foreign('advert_id')->references('id')->on('adverts');
            $table->foreign('option_id')->references('id')->on('options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advert_option');
    }
}

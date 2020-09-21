<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAdvertsAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adverts', function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('street_id')->nullable()->after('city_id');
        });
        Schema::table('adverts', function (Blueprint $blueprint) {
            $blueprint->foreign('street_id')->references('id')->on('streets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adverts', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['street_id']);
        });
        Schema::table('adverts', function (Blueprint $blueprint) {
            $blueprint->dropColumn('street_id');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGeoFieldsToAdvert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->unsignedBigInteger('subway_id')->nullable();
            $table->unsignedBigInteger('administrative_id')->nullable();
        });

        Schema::table('adverts', function (Blueprint $table) {
            $table->foreign('subway_id')->references('id')->on('subways');
            $table->foreign('administrative_id')->references('id')->on('administrative');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->dropForeign('subway_id');
            $table->dropColumn('subway_id');
            $table->dropForeign('administrative_id');
            $table->dropColumn('administrative_id');
        });
    }
}

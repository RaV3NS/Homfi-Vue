<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGeoTablesTransliField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->string('translit', 250);
        });
        Schema::table('administrative', function (Blueprint $table) {
            $table->string('translit', 250);
        });
        Schema::table('streets', function (Blueprint $table) {
            $table->string('translit', 250);
        });
        Schema::table('subways', function (Blueprint $table) {
            $table->string('translit', 250);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('translit');
        });
        Schema::table('administrative', function (Blueprint $table) {
            $table->dropColumn('translit');
        });
        Schema::table('streets', function (Blueprint $table) {
            $table->dropColumn('translit');
        });
        Schema::table('subways', function (Blueprint $table) {
            $table->dropColumn('translit');
        });
    }
}

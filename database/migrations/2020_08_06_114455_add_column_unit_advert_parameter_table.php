<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddColumnUnitAdvertParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parameters', function (Blueprint $table) {
            $table->string('unit', 10)->nullable();
        });

        DB::update('update parameters set unit = "м²" where `key` IN (?)', ['total_space']);
        DB::update('update parameters set unit = "м²" where `key` IN (?)', ['living_space']);
        DB::update('update parameters set unit = "м²" where `key` IN (?)', ['kitchen_space']);
        DB::update('update parameters set unit = "м" where `key` = (?)', ['height']);
        DB::update('update parameters set unit = "г." where `key` = (?)', ['build_year']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parameters', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
}

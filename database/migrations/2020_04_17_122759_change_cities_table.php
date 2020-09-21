<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $blueprint) {
            $blueprint->uuid('uuid')->after('id')->index();
            $blueprint->string('name_ru')->after('name');
            $blueprint->foreign('district_id')->references('id')->on('districts');
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
            $blueprint->dropColumn('uuid');
            $blueprint->dropColumn('name_ru');
            $blueprint->dropForeign(['district_id']);
        });
    }
}

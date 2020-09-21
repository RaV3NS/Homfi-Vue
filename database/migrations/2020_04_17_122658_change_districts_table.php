<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('districts', function (Blueprint $blueprint) {
            $blueprint->uuid('uuid')->after('id')->index();
            $blueprint->string('name_ru')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('districts', function (Blueprint $blueprint) {
            $blueprint->dropColumn('uuid');
            $blueprint->dropColumn('name_ru');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCitiesIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $blueprint) {
            $blueprint->index(['name']);
            $blueprint->index(['name_ru']);
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
            $blueprint->dropIndex(['name']);
            $blueprint->dropIndex(['name_ru']);
        });
    }
}

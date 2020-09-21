<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('districts', function (Blueprint $blueprint) {
            $blueprint->softDeletes();
        });

        Schema::table('regions', function (Blueprint $blueprint) {
            $blueprint->softDeletes();
        });

        Schema::table('cities', function (Blueprint $blueprint) {
            $blueprint->softDeletes();
        });

        Schema::table('streets', function (Blueprint $blueprint) {
            $blueprint->softDeletes();
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
            $blueprint->dropSoftDeletes();
        });

        Schema::table('regions', function (Blueprint $blueprint) {
            $blueprint->dropSoftDeletes();
        });

        Schema::table('cities', function (Blueprint $blueprint) {
            $blueprint->dropSoftDeletes();
        });

        Schema::table('streets', function (Blueprint $blueprint) {
            $blueprint->dropSoftDeletes();
        });
    }
}

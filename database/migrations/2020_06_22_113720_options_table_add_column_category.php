<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OptionsTableAddColumnCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            //$table->renameColumn('name_uk', 'key');
        });

        Schema::table('options', function (Blueprint $table) {
            //$table->string('name_uk');
            $table->string('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->dropColumn('name_uk');
            $table->renameColumn('key', 'name_uk');
        });
    }
}

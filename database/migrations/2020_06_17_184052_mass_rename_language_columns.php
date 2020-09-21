<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MassRenameLanguageColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->renameColumn('name', 'name_uk');
        });
        Schema::table('contents', function (Blueprint $table) {
            $table->renameColumn('body_ua', 'body_uk');
        });
        Schema::table('districts', function (Blueprint $table) {
            $table->renameColumn('name', 'name_uk');
        });
        Schema::table('options', function (Blueprint $table) {
            $table->renameColumn('name', 'key');
            $table->string('name_uk');
            $table->string('name_ru');
        });
        Schema::table('parameters', function (Blueprint $table) {
            $table->renameColumn('name', 'key');
            $table->string('name_ru');
            $table->string('name_uk');
        });
        Schema::table('regions', function (Blueprint $table) {
            $table->renameColumn('name', 'name_uk');
        });
        Schema::table('streets', function (Blueprint $table) {
            $table->renameColumn('name', 'name_uk');
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
            $table->renameColumn('name_uk', 'name');
        });
        Schema::table('contents', function (Blueprint $table) {
            $table->renameColumn('body_uk', 'body_ua');
        });
        Schema::table('districts', function (Blueprint $table) {
            $table->renameColumn('name_uk', 'name');
        });
        Schema::table('options', function (Blueprint $table) {
            $table->renameColumn('key', 'name');
            $table->dropColumn('name_ru');
            $table->dropColumn('name_uk');
        });
        Schema::table('parameters', function (Blueprint $table) {
            $table->renameColumn('name_uk', 'name');
            $table->dropColumn('name_ru');
            $table->dropColumn('key');
        });
        Schema::table('regions', function (Blueprint $table) {
            $table->renameColumn('name_uk', 'name');
        });
        Schema::table('streets', function (Blueprint $table) {
            $table->renameColumn('name_uk', 'name');
        });
    }
}

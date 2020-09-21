<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueIndexAdvertOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advert_parameter', function (Blueprint $table) {
            $table->unique(['advert_id', 'parameter_id', 'value']);
            $table->index('advert_id');
        });

        Schema::table('advert_option', function (Blueprint $table) {
            $table->unique(['advert_id', 'option_id']);
            $table->index('advert_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advert_parameter', function (Blueprint $table) {
            $table->dropIndex('advert_id');
            $table->dropunique('advert_id_parameter_id_value');
        });

        Schema::table('advert_option', function (Blueprint $table) {
            $table->dropIndex('advert_id');
            $table->dropunique('advert_id_option_id');
        });
    }
}

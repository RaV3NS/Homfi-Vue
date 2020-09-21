<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdvertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adverts', function(Blueprint $table) {
            $table->renameColumn('rooms', 'room_count');
            $table->string('type')->nullable()->change();
            $table->boolean('show_contacts')->default(1)->change();
            $table->string('title')->nullable()->change();
            $table->string('lng')->nullable()->change();
            $table->string('lat')->nullable()->change();
            $table->string('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adverts', function(Blueprint $table) {
            $table->renameColumn('room_count', 'rooms');
        });
    }
}

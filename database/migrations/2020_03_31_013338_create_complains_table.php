<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id');
            $table->foreignId('user_id')->nullable();
            $table->string('email');
            $table->string('reason');
            $table->text('body')->nullable();
            $table->string('status');
            $table->timestamps();
        });

        Schema::table('complains', function (Blueprint $table) {
            $table->foreign('advert_id')->references('id')->on('adverts');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complains');
    }
}

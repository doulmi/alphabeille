<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPunchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_punches', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->date('punch_time');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['user_id', 'punch_time']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_punches');
    }
}

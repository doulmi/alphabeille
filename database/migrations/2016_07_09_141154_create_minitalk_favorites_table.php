<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinitalkFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minitalk_favorites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('minitalk_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('minitalk_id')->references('id')->on('minitalks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("minitalk_favorites");
    }
}

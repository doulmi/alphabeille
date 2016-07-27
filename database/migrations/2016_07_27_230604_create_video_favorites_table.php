<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_favorites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('video_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
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
        Schema::drop("video_favorites");
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTalkshowCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talkshow_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('talkshow_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('content');
            $table->foreign('talkshow_id')->references('id')->on('talkshows')->onDelete('cascade');
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
        Schema::drop('talkshow_comments');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCommentLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_comment_likes', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('comment_id')->unsigned();
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['user_id', 'comment_id']);
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
        Schema::drop('user_comment_likes');
    }
}

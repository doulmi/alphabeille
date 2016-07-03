<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonCollectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_collects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lesson_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
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
        Schema::drop('lesson_collects');
    }
}

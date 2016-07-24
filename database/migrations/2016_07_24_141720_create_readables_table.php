<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReadablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('avatar');
            $table->string('duration');
            $table->string('audio_url');
            $table->string('download_url');
            $table->text('content');
            $table->string('keywords');
            $table->boolean('is_published')->default(true);
            $table->timestamp('publish_at');
            $table->integer('likes')->default(0);
            $table->boolean('free')->default(true);
            $table->integer('views')->default(0);
            $table->string('type');
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
        Schema::drop('readables');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinitalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minitalks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('avatar');
            $table->string('duration');
            $table->string('audio_url');
            $table->string('download_url');
            $table->text('content');
            $table->string('keywords');
            $table->text('wechat_part');
            $table->boolean('is_published');
            $table->timestamp('publish_at');
            $table->integer('likes')->default(0);
            $table->boolean('free')->default(true);
            $table->integer('views')->default(0);
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
        Schema::drop('minitalks');
    }
}

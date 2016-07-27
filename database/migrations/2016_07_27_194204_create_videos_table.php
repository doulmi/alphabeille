<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('avatar');
            $table->string('video_url');
            $table->string('download_url');
            $table->text('content');
            $table->text('parsed_content');
            $table->string('keywords');
            $table->boolean('is_published');
            $table->timestamp('publish_at');
            $table->softDeletes();
            $table->string('slug')->nullable();
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
        Schema::drop('videos');
    }
}

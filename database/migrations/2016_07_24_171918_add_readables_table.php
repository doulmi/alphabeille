<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->integer('readable_id')->unsigned();
            $table->foreign('readable_id')->references('id')->on('readables')->onDelete('cascade');
        });

        Schema::table('talkshows', function (Blueprint $table) {
            $table->integer('readable_id')->unsigned();
            $table->foreign('readable_id')->references('id')->on('readables')->onDelete('cascade');
        });

        Schema::table('minitalks', function (Blueprint $table) {
            $table->integer('readable_id')->unsigned();
            $table->foreign('readable_id')->references('id')->on('readables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign('lessons_readable_id_foreign');
            $table->dropColumn('readable_id');
        });

        Schema::table('talkshows', function (Blueprint $table) {
            $table->dropForeign('talkshows_readable_id_foreign');
            $table->dropColumn('readable_id');
        });

        Schema::table('minitalks', function (Blueprint $table) {
            $table->dropForeign('minitalks_readable_id_foreign');
            $table->dropColumn('readable_id');
        });
    }
}

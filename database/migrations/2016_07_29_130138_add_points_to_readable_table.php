<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPointsToReadableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->text('points')->default('');
        });
        Schema::table('talkshows', function (Blueprint $table) {
            $table->text('points')->default('');
        });
        Schema::table('minitalks', function (Blueprint $table) {
            $table->text('points')->default('');
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->text('points')->default('');
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
            $table->dropColumn('points');
        });
        Schema::table('talkshows', function (Blueprint $table) {
            $table->dropColumn('points');
        });
        Schema::table('minitalks', function (Blueprint $table) {
            $table->dropColumn('points');
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
}

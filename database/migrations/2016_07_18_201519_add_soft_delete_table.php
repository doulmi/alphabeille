<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('lessons', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('talkshows', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('minitalks', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('talkshows', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('minitalks', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}

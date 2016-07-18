<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->string('slug')->index();
        });
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('slug')->index();
        });
        Schema::table('talkshows', function (Blueprint $table) {
            $table->string('slug')->index();
        });
        Schema::table('minitalks', function (Blueprint $table) {
            $table->string('slug')->index();
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
            $table->dropColumn('slug');
        });
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('talkshows', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('minitalks', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}

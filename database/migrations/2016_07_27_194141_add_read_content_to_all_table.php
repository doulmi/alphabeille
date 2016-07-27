<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadContentToAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('real_content')->index();
        });
        Schema::table('talkshows', function (Blueprint $table) {
            $table->string('real_content')->index();
        });
        Schema::table('minitalks', function (Blueprint $table) {
            $table->string('real_content')->index();
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
            $table->dropColumn('real_content');
        });
        Schema::table('talkshows', function (Blueprint $table) {
            $table->dropColumn('real_content');
        });
        Schema::table('minitalks', function (Blueprint $table) {
            $table->dropColumn('real_content');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupportsToVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('listener_id')->unsigned()->default(5);
            $table->integer('translator_id')->unsigned()->default(3);
            $table->integer('verifier_id')->unsigned()->default(3);
            $table->integer('lastMonthViews');
            $table->string('originSrc');

            $table->foreign('listener_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('translator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verifier_id')->references('id')->on('users')->onDelete('cascade');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign('videos_listener_id_foreign');
            $table->dropForeign('videos_translator_id_foreign');
            $table->dropForeign('videos_verifier_id_foreign');

            $table->dropColumn('listener_id');
            $table->dropColumn('translator_id');
            $table->dropColumn('verifier_id');
            $table->dropColumn('lastMonthViews');
            $table->dropColumn('originSrc');
        });
    }
}

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
            $table->text('parsed_content');
            $table->text('parsed_content_zh_CN');
        });
        Schema::table('talkshows', function (Blueprint $table) {
            $table->text('parsed_content');
        });
        Schema::table('minitalks', function (Blueprint $table) {
            $table->text('parsed_content');
            $table->text('parsed_wechat_part');
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
            $table->dropColumn('parsed_content');
            $table->dropColumn('parsed_content_zh_CN');
        });
        Schema::table('talkshows', function (Blueprint $table) {
            $table->dropColumn('parsed_content');
        });
        Schema::table('minitalks', function (Blueprint $table) {
            $table->dropColumn('parsed_content');
            $table->dropColumn('parsed_wechat_part');
        });
    }
}

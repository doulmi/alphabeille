<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishToTalkshowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talkshows', function (Blueprint $table) {
            $table->boolean('is_published');
            $table->timestamp('publish_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talkshows', function (Blueprint $table) {
            $table->dropColumn('is_published');
            $table->dropColumn('publish_at');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFixtopToDiscussions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discussions', function (Blueprint $table) {
            $table->timestamp('fixtop_expire_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discussions', function (Blueprint $table) {
            $table->dropColumn('fixtop_expire_at');
        });
    }
}

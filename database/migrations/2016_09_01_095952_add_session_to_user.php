<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSessionToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_session_id')->default('0000000000000000000000000000000000000000');
            $table->string('last_ip');
            $table->boolean('last_login_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_session_id');
            $table->dropColumn('last_ip');
            $table->dropColumn('last_login_foreign');
        });
    }
}

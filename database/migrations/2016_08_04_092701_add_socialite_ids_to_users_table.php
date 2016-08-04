<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialiteIdsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('qq_id')->index()->unique()->nullable();
            $table->string('facebook_id')->index()->unique()->nullable();
            $table->string('wechat_id')->index()->unique()->nullable();
            $table->integer('birthYear');
            $table->string('location');
            $table->boolean('hasEmail');
            $table->enum('sex', ['male', 'female', 'unknown']);
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
            $table->dropColumn('qq_id');
            $table->dropColumn('facebook_id');
            $table->dropColumn('wechat_id');
            $table->dropColumn('birthYear');
            $table->dropColumn('location');
            $table->dropColumn('hasEmail');
            $table->dropColumn('sex');
        });
    }
}

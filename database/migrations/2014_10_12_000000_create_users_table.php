<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('confirmed')->default(0);
            $table->string('confirmation_code', 64)->nullable();
            $table->string('avatar');
            $table->string('wechat');
            $table->string('QQ');
            $table->integer('download')->default(6);
            $table->integer('downloadMax')->default(6);
            $table->timestamp('last_login_at')->nullbale();
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVocabulariesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('vocabularies', function (Blueprint $table) {
      $table->increments('id');
      $table->string('title');
      $table->timestamp('date');
      $table->text('content');
      $table->text('parsedContent');
      $table->string('hash');
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
    Schema::drop('vocabularies');
  }
}

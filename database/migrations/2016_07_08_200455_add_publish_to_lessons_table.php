<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishToLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            /* 该课程是否发布，而非是否已经发布 */
            $table->boolean('is_published');
            /* 该课程发布的日期, 可以是未来 */
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
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('is_published');
            $table->dropColumn('publish_at');
        });
    }
}

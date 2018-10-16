<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestLessonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_lesson', function (Blueprint $table) {
            $table->integer('guest_id')->unsigned();
            $table->integer('lesson_id')->unsigned();

            $table->foreign('guest_id')->references('id')->on('guests')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->tinyInteger('is_like')->default(0)->comment('点赞');
            $table->tinyInteger('is_collect')->default(0)->comment('收藏');
            $table->tinyInteger('is_pay')->default(0)->comment('购买');
            $table->integer('last_section')->nullable()->comment('最后观看过的章节');
            $table->string('sections')->default('[]')->comment('观看过的章节');
            $table->timestamp('add_date')->nullable()->comment('加入时间');
            $table->timestamp('collect_date')->nullable()->comment('收藏时间');

            $table->primary(['guest_id', 'lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_lesson');
    }
}

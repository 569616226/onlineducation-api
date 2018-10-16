<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('章节名称');
            $table->boolean('is_free')->comment('是否免费，1：免费，0：收费');
            $table->string('play_times',50)->default(0)->comment('腾讯视频播放次数');

            $table->integer('video_id')->unsigned();
            $table->foreign('video_id')->references('id')->on('videos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('lesson_id')->unsigned();
            $table->foreign('lesson_id')->references('id')->on('lessons')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->softDeletes();
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
        Schema::dropIfExists('sections');
    }
}

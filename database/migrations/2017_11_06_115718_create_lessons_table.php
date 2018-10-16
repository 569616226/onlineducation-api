<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('课程名称');
            $table->text('title')->comment('课程副标题');
            $table->tinyInteger('type')->comment('课程类型 1：免费  2：付费 3：vip 4：访谈课程');

            $table->integer('educational_id')->nullable()->unsigned();
            $table->foreign('educational_id')->references('id')->on('educationals')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('nav_id')->unsigned();
            $table->foreign('nav_id')->references('id')->on('navs')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('teacher_id')->unsigned()->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('out_play_times')->default(0)->comment('腾讯视频播放次数，显示数据');
            $table->integer('status')->default(1)->comment('课程状态： 1：未上架， 2：已下架 ，3：已上架');
            $table->string('pictrue')->comment('课程封面');
            $table->double('price', 10, 2)->default(0)->comment('课程价格');
            $table->text('learning')->nullable()->comment('可以学到什么');
            $table->integer('out_like')->default(0)->comment('点赞数 显示数据');
            $table->longText('for')->nullable()->comment('试用人群');
            $table->longText('describle')->nullable()->comment('课程描述');
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
        Schema::dropIfExists('lessons');
    }
}

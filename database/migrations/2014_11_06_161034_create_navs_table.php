<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('栏目名称');
            $table->string('pictrue')->comment('栏目图片');
            $table->string('nav_lesson_ids')->nullable()->comment('栏目推荐课程');
            $table->string('nav_train_ids')->nullable()->comment('栏目推荐活动');
            $table->tinyInteger('is_hide')->default(0)->comment('是否显示');
            $table->tinyInteger('type')->default(0)->comment('栏目类型：0 课程，1：会销');
            $table->tinyInteger('ordered')->comment('栏目排序');
            $table->tinyInteger('order_type')->default(1)->comment('视频排序方式 1：更新是时间 2：视频播放次数');
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
        Schema::dropIfExists('navs');
    }
}

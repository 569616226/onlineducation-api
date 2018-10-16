<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('index_type')->default(1)->comment('首页设置类型 1：最新更新 2：自定义');
            $table->integer('index_count')->default(4)->comment('type 为1 时，视频显示数量 4,6,8');
            $table->integer('vip_send_seting')->default(5)->comment('会员提醒周期 5,3 天');
            $table->string('top_lesson_ids')->nullable()->comment('最近更新课程id');
            $table->string('top_train_ids')->nullable()->comment('最近更新活动id');
            $table->text('wechat_sub')->nullable()->comment('微信关注回复');
            $table->integer('sign_start_time')->comment('签到开始时间，（活动提前的分钟）');
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
        Schema::dropIfExists('settings');
    }
}

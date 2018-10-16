<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('会议名称');
            $table->tinyInteger('status')->default(0)->comment('状态 : 0 未开始 , 1 进行中，2 已结束');
            $table->string('title')->comment('会议副标题');
            $table->string('pic')->comment('活动封面');
            $table->dateTime('start_at')->comment('会议开始时间');
            $table->dateTime('end_at')->comment('会议结束时间');
            $table->string('address')->comment('会议地址');
            $table->longText('discrible')->comment('会议详情');
            $table->string('qr_code')->nullable()->comment('签到二维码');
            $table->string('creator')->comment('创建人');
            $table->string('collect_guest_ids')->defalut('[]')->comment('收藏人id集合');

            $table->integer('nav_id')->unsigned();
            $table->foreign('nav_id')->references('id')->on('navs')
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
        Schema::dropIfExists('trains');
    }
}

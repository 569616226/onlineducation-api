<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('姓名');
            $table->tinyInteger('status')->default(0)->comment('签到状态 : 0 未签到 , 1 已签到');
            $table->tinyInteger('inser_type')->default(0)->comment(' 渠道来源: 0 表格导入 , 1 公众号报名');
            $table->string('referee')->nullable()->comment(' 推荐人');
            $table->string('tel')->comment(' 手机');
            $table->string('company')->comment(' 公司名称');
            $table->string('offer')->comment(' 职位');

            $table->integer('train_id')->unsigned();
            $table->foreign('train_id')->references('id')->on('trains')
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
        Schema::dropIfExists('signs');
    }
}

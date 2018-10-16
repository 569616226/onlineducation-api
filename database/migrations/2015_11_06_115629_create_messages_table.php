<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title')->comment('消息标题');
            $table->text('content')->comment('消息内容');
            $table->string('label')->nullable()->comment('接收者：只在列表显示');
            $table->string('picture')->nullable()->comment('图文图片');
            $table->string('url')->nullable()->comment('图文链接');
            $table->integer('type')->default(0)->comment('消息状态 0：未读，1：已读');

            $table->integer('user_id')->nullable()->comment('user')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('messages');
    }
}

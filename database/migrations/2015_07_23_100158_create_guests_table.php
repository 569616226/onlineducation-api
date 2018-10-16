<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->comment('姓名');
            $table->string('nickname')->comment('昵称');
            $table->string('referee')->nullable()->comment('推荐人');
            $table->string('company')->nullable()->comment('公司名');
            $table->string('offer')->nullable()->comment('职位');
            $table->string('phone')->nullable()->comment('手机');
            $table->string('openid')->comment('微信id');
            $table->boolean('is_subscribe')->default(1)->comment('是否关注公众号 默认没有关注');

            $table->integer('vip_id')->nullable()->comment('vip')->unsigned();
            $table->foreign('vip_id')->references('id')->on('vips')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->string('picture', 255)->nullable()->comment('头像');
            $table->string('position')->nullable()->comment('位置');
            $table->boolean('gender')->comment('性别 true-男 false-女');

            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('guests');
    }
}

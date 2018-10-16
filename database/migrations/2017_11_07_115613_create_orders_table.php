<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('订单名称');
            $table->string('order_no')->comment('订单编号');
            $table->string('pictrue')->nullable()->comment('商品图片');
            $table->string('title')->comment('商品标题');
            $table->integer('type')->comment('订单类型 1：课程订单 2：会员订单');
            $table->integer('status')->default(2)->comment('订单状态 1：已付款  2：待付款  3：已关闭');
            $table->integer('order_type_id')->comment('订单对应课程或者vip的id');
            $table->integer('guest_id')->comment('购买者')->unsigned();
            $table->foreign('guest_id')->references('id')->on('guests')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('pay_type')->comment('支付方式： 1：微信支付');
            $table->double('price',10,2)->comment('价格');
            $table->integer('mouth')->nullable()->comment('开通时长,多少月');
            $table->timestamp('start')->nullable()->comment('开通时间');
            $table->timestamp('end')->nullable()->comment('到期时间');
            $table->timestamp('pay_date')->nullable()->comment('支付时间');
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
        Schema::dropIfExists('orders');
    }
}

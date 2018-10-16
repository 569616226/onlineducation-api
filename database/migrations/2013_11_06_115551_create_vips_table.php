<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('会员价格名称');
            $table->integer('status')->default(2)->comment('会员价格状态 1：已上架  2：未上架  3：已下架');
            $table->integer('expiration')->comment('有效期');
            $table->double('price',10,2)->comment('价格');
            $table->integer('count')->default(0)->comment('出售数量');
            $table->text('describle')->comment('价格描述');
            $table->timestamp('up')->nullable()->comment('上架时间');
            $table->timestamp('down')->nullable()->comment('下架时间');
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
        Schema::dropIfExists('vips');
    }
}

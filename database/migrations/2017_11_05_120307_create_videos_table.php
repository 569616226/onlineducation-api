<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fileId')->comment('腾讯视频fileID');
            $table->string('name')->nullable()->comment('腾讯视频名称');
            $table->string('status')->default(4)->comment('视频状态， -1：未上传完成，不存在；0：初始化，暂未使用；1：审核不通过，暂未使用；2：正常；3：暂停；4：转码中；5：发布中；6：删除中；7：转码失败；100：已删除');
            $table->string('edk_key')->nullable()->comment('视频加密密钥');
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
        Schema::dropIfExists('videos');
    }
}

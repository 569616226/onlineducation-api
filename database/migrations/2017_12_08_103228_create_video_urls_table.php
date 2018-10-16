<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->comment('视频链接');
            $table->string('size')->nullable()->comment('视频大小');
            $table->string('duration')->comment('视频时长');

            $table->integer('video_id')->nullable()->comment('video')->unsigned();
            $table->foreign('video_id')->references('id')->on('videos')
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
        Schema::dropIfExists('video_urls');
    }
}

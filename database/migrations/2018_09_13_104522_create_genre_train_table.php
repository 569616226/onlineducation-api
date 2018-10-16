<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenreTrainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genre_train', function (Blueprint $table) {
            $table->integer('genre_id')->unsigned();
            $table->integer('train_id')->unsigned();

            $table->foreign('genre_id')->references('id')->on('genres')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('train_id')->references('id')->on('trains')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['genre_id', 'train_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genre_train');
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreTrainTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genre_train')->insert([
            [
                'genre_id' => 1,
                'train_id' => 1,
            ]
        ]);
    }
}

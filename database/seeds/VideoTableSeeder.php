<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(\App\Models\Video::class,config('other.seeder_count'))->create([
            'status' => -1,
        ]);
        factory(\App\Models\Video::class,config('other.seeder_count'))->create([
            'status' => 0,
        ]);
        factory(\App\Models\Video::class,config('other.seeder_count'))->create([
            'status' => 5,
        ]);
        factory(\App\Models\Video::class,config('other.seeder_count'))->create([
            'status' => 6,
        ]);
        factory(\App\Models\Video::class,config('other.seeder_count'))->create([
            'status' => 7,
        ]);
        factory(\App\Models\Video::class,config('other.seeder_count'))->create([
            'status' => 100,
        ]);
    }
}

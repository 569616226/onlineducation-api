<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoUrlTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\VideoUrl::class,config('other.seeder_count'))->create([
            'video_id' => 1,
            'duration' => 200,
        ]);
    }
}

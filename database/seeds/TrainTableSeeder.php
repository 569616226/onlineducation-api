<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(\App\Models\Train::class,config('other.seeder_count'))->create([
            'status' =>0
        ]);
        factory(\App\Models\Train::class,config('other.seeder_count'))->create([
            'status' => 1
        ]);
        factory(\App\Models\Train::class,config('other.seeder_count'))->create([
            'status' => 2
        ]);
    }
}

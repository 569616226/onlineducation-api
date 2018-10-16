<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Sign::class,config('other.seeder_count'))->create([
                'status' => 0,
                'inser_type' => 0,
                'train_id' => 1,
        ]);
        factory(\App\Models\Sign::class,config('other.seeder_count'))->create([
                'status' => 1,
                'inser_type' => 0,
                'train_id' => 1,
        ]);
        factory(\App\Models\Sign::class,config('other.seeder_count'))->create([
                'status' => 0,
                'inser_type' => 1,
                'train_id' => 1,
        ]);
        factory(\App\Models\Sign::class,config('other.seeder_count'))->create([
                'status' => 1,
                'inser_type' => 1,
                'train_id' => 1,
        ]);
    }
}

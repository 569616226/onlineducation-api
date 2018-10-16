<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvertTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Advert::class,config('other.seeder_count'))->create([
            'type' =>  0
        ]);
        factory(\App\Models\Advert::class,config('other.seeder_count'))->create([
            'type' =>  1
        ]);
    }
}

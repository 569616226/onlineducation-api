<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(\App\Models\Vip::class,config('other.seeder_count'))->create([
            'status' => 1,
        ]);
        factory(\App\Models\Vip::class,config('other.seeder_count'))->create([
            'status' => 2,
        ]);
        factory(\App\Models\Vip::class,config('other.seeder_count'))->create([
            'status' => 3,
        ]);
    }
}

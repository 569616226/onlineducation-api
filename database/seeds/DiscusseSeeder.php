<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscusseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Discusse::class,config('other.seeder_count'))->create([
            'is_better' => 0
        ]);
        factory(\App\Models\Discusse::class,config('other.seeder_count'))->create([
            'is_better' => 1
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Section::class,config('other.seeder_count'))->create([
            'is_free' => 0
        ]);
        factory(\App\Models\Section::class,config('other.seeder_count'))->create([
            'is_free' => 1
        ]);
    }
}

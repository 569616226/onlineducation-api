<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GerenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Genre::class,config('other.seeder_count'))->create();
    }
}

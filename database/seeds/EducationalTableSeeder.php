<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(\App\Models\Educational::class,config('other.seeder_count'))->create();
    }
}

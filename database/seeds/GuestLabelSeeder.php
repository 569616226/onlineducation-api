<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guest_label')->insert([
           [
               'guest_id' => 1,
               'label_id' => 1,
           ]
        ]);
    }
}

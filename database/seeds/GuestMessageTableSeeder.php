<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestMessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guest_message')->insert([
            [
                'guest_id' => 1,
                'message_id' => 1,
            ]

        ]);
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestLessonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guest_lesson')->insert([
            [
                'guest_id' => 1,
                'lesson_id' => 1,
                'is_like' => 1,
                'is_collect' => 1,
                'is_pay' => 1,
                'sections' => '[1]',
                'last_section' => 1,
                'add_date' => now(),
                'collect_date' => now()
            ]
        ]);
    }
}

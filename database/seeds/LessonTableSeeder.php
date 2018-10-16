<?php

use Illuminate\Database\Seeder;

class LessonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 1,
            'status' => 3
        ]);

        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 1,
            'status' => 1
        ]);
        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 1,
            'status' => 2
        ]);

        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 2,
            'status' => 3
        ]);

        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 2,
            'status' => 1
        ]);
        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 2,
            'status' => 2
        ]);

        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 3,
            'status' => 3
        ]);

        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 3,
            'status' => 1
        ]);
        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 3,
            'status' => 2
        ]);

        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 4,
            'status' => 3
        ]);

        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 4,
            'status' => 1
        ]);
        factory(\App\Models\Lesson::class,config('other.seeder_count'))->create([
            'type'=> 4,
            'status' => 2
        ]);
    }
}

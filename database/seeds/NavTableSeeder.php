<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Nav::class, config('other.seeder_count'))->create([
            'ordered'    => 1,
            'order_type' => 1,
            'is_hide'    => 0,
            'type'       => 0,
        ]);

        factory(\App\Models\Nav::class, config('other.seeder_count'))->create([
            'ordered'    => 2,
            'order_type' => 1,
            'is_hide'    => 0,
            'type'       => 1,
        ]);

        factory(\App\Models\Nav::class, config('other.seeder_count'))->create([
            'ordered'    => 3,
            'order_type' => 1,
            'is_hide'    => 1,
            'type'       => 0,
        ]);

        factory(\App\Models\Nav::class, config('other.seeder_count'))->create([
            'ordered'    => 4,
            'order_type' => 1,
            'is_hide'    => 1,
            'type'       => 1,
        ]);


        factory(\App\Models\Nav::class, config('other.seeder_count'))->create([
            'ordered'    => 5,
            'order_type' => 2,
            'is_hide'    => 0,
            'type'       => 0,
        ]);

        factory(\App\Models\Nav::class, config('other.seeder_count'))->create([
            'ordered'    => 6,
            'order_type' => 2,
            'is_hide'    => 0,
            'type'       => 1,
        ]);
        factory(\App\Models\Nav::class, config('other.seeder_count'))->create([
            'ordered'    => 7,
            'order_type' => 2,
            'is_hide'    => 1,
            'type'       => 0,
        ]);
        factory(\App\Models\Nav::class, config('other.seeder_count'))->create([
            'ordered'    => 8,
            'order_type' => 2,
            'is_hide'    => 1,
            'type'       => 1,
        ]);

    }
}

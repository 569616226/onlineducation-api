<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Order::class,config('other.seeder_count'))->create([
            'type' => 1,
            'status' => 1,
            'order_type_id' => 1,
        ]);
        factory(\App\Models\Order::class,config('other.seeder_count'))->create([
            'type' => 1,
            'status' => 2,
            'order_type_id' => 1,
        ]);
        factory(\App\Models\Order::class,config('other.seeder_count'))->create([
            'type' => 1,
            'status' => 3,
            'order_type_id' => 1,
        ]);

        factory(\App\Models\Order::class,config('other.seeder_count'))->create([
            'type' => 2,
            'status' => 1,
            'order_type_id' => 1,
        ]);
        factory(\App\Models\Order::class,config('other.seeder_count'))->create([
            'type' => 2,
            'status' => 2,
            'order_type_id' => 1,
        ]);
        factory(\App\Models\Order::class,config('other.seeder_count'))->create([
            'type' => 2,
            'status' => 3,
            'order_type_id' => 1,
        ]);
    }
}

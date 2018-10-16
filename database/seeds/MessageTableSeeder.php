<?php

use Illuminate\Database\Seeder;

class MessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Message::class,config('other.seeder_count'))->create([
                'type'=>0
        ]);
        factory(\App\Models\Message::class,config('other.seeder_count'))->create([
                'type'=>1
        ]);
        factory(\App\Models\Message::class,config('other.seeder_count'))->create([
                'type'=>0,
                'user_id'=>null
        ]);
        factory(\App\Models\Message::class,config('other.seeder_count'))->create([
                'type'=>1,
                'user_id'=>null
        ]);
    }
}

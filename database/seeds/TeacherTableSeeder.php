<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teachers')->insert([
            [
                'id' => 1,
                'name' => '你好',
                'office' => '你好',
                'avatar' => 'http://wx.qlogo.cn/mmopen/C6FZHjYVZVCpzGgCMYcQliab7RzjwZBfSgGiacJA4cbtgPhkNSBLWyZxsVIYOPm8SDL3ib7a0Gl5xbnQo8B800iafQvNN1UaA1uP/0',
                'describle' => '这是一个很棒的讲师',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(\App\Models\User::class)->create([
            'name' => 'admin',
            'nickname' => '超级管理员',
            'password' => bcrypt('admin'),
            'gender' => 1,
            'frozen' => 0,
        ]);

        $user_guest =  factory(\App\Models\User::class)->create([
            'name' => 'guest',
            'nickname' => '访客',
            'password' => bcrypt('guest'),
            'gender' => 2,
            'frozen' => 0,
        ]);

        $user_others = factory(\App\Models\User::class,config('other.seeder_count'))->create([
            'gender' => 1,
            'frozen' => 1,
        ]);

        $user->assignRole('super_admin');
        $user_guest->assignRole('marketer');

        foreach($user_others as $user_other){
            $user_other->assignRole('marketer');
        }

    }
}
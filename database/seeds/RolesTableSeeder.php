<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Role::create(['name' => 'super_admin', 'guard_name' => 'api', 'display_name' => '管理员']);
        Role::create(['name' => 'marketer', 'guard_name' => 'api', 'display_name' => '运营']);
        Role::create(['name' => 'teacher', 'guard_name' => 'api', 'display_name' => '讲师']);
        Role::create(['name' => 'guest', 'guard_name' => 'api', 'display_name' => '用户']);
    }
}

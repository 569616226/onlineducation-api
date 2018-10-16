<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            [
                'id' => 1,
                'user_id' => null,
                'name' => 'wechat_token',
                'secret' => 'BFBoSMd5uY1aw9MWBb7IHYaydXW3SPte98Ps9tME',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => now(),
                'updated_at' => now()->addDays(3),
            ],
            [
                'id' => 2,
                'user_id' => null,
                'name' => 'Laravel Password Grant Client',
                'secret' => 'HrBPKElM9GRdis49AYI2LtyU3ElDdlijzLeC9ujJ',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => now(),
                'updated_at' => now()->addDays(3),
            ],
        ]);


        DB::table('oauth_personal_access_clients')->insert([
            [
                'id' => 1,
                'client_id' => 1,
                'created_at' => now(),
                'updated_at' => now()->addDays(3),
            ]
        ]);
    }
}

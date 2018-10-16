<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $guest = factory(\App\Models\Guest::class)->create([

            'name'     => 'Dream',
            'nickname' => 'Dream',
            'phone'    => null,
            'openid'   => 'oDMF40TjhXnYMy0e5RLPX3ZU-kzw',
            'picture'  => 'http://wx.qlogo.cn/mmopen/C6FZHjYVZVCpzGgCMYcQliab7RzjwZBfSgGiacJA4cbtgPhkNSBLWyZxsVIYOPm8SDL3ib7a0Gl5xbnQo8B800iafQvNN1UaA1uP/0',
            'gender'   => 2,
            'position' => '北京',
            'referee'  => 'referee',
            'company'  => 'company',
            'offer'    => 'offer',

        ]);

        $guest->assignRole('guest');
    }
}

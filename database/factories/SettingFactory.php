\<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Setting::class, function (Faker $faker) {
    return [
        'index_type' => 1,
        'index_count' => 4,
        'vip_send_seting' => 10,
        'sign_start_time' => 10,
        'top_lesson_ids' => [],
        'wechat_sub' => $faker->name,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

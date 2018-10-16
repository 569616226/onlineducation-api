<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Vip::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'status' => 1,
        'expiration' => 1,
        'price' => 1.00,
        'count' => 1,
        'describle' => $faker->paragraph,
        'up' => now(),
        'down' => now()->addDays(3),
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

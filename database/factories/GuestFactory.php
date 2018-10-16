<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Guest::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'nickname' => $faker->name,
        'phone' => $faker->phoneNumber,
        'openid' => $faker->name,
        'picture' => $faker->imageUrl(),
        'referee' => $faker->name,
        'company' => $faker->name,
        'offer' => $faker->name,
        'gender' => $faker->boolean,
        'is_subscribe' => true,
        'position' => $faker->address,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

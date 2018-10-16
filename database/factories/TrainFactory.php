<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Train::class, function (Faker $faker) {
    return [
        'name'              => $faker->name,
        'title'             => $faker->name,
        'status'            => array_random([0, 1, 2]),
        'nav_id'            => 2,
        'pic'               => $faker->imageUrl(400, 400),
        'start_at'          => now(),
        'end_at'            => now(),
        'address'           => $faker->address,
        'discrible'         => $faker->name,
        'collect_guest_ids' => [],
        'qr_code'           => $faker->imageUrl(40, 40),
        'creator'           => $faker->name,
    ];
});

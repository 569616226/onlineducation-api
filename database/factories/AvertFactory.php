<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Advert::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'path' => $faker->imageUrl(),
        'type' => 0,
        'order' => 1,
        'url' => $faker->imageUrl(),
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

<?php

use Faker\Generator as Faker;
use Carbon\Carbon;


$factory->define(\App\Models\Teacher::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'office' => $faker->name,
        'avatar' => $faker->imageUrl(),
        'describle' => $faker->paragraph,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

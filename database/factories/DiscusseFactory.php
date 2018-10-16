<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Discusse::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph,
        'lesson_id' => 1,
        'guest_id' => 1,
        'is_better' => false,
        'pid' => 0,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

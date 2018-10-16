<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Nav::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'pictrue' => $faker->imageUrl(),
        'ordered' => 0,
        'order_type' => 1,
        'is_hide' => 0,
        'type' => 0,
        'nav_train_ids' => [],
        'nav_lesson_ids' => [],
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Section::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'is_free' => 0,
        'lesson_id' => 1,
        'video_id' => 1,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

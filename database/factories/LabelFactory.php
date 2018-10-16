<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Label::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

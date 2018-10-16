<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Educational::class, function (Faker $faker) {
    return [
        'name' =>$faker->name,
        'content' =>$faker->paragraph,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

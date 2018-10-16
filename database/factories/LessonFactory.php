\<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Lesson::class, function (Faker $faker) {
    return [
        'name'           => $faker->name,
        'title'          => $faker->paragraph,
        'type'           => 1,
        'teacher_id'     => 1,
        'nav_id'         => 1,
        'educational_id' => 1,
        'status'         => 2,
        'pictrue'        => $faker->imageUrl(750, 440),
        'price'          => 0.00,
        'out_play_times' => 0,
        'out_like'       => 0,
        'learning'       => $faker->name.'--'.$faker->name.'--',
        'for'            => $faker->paragraph,
        'describle'      => $faker->paragraph,
        'created_at'     => now(),
        'updated_at'     => now()->addDays(3),
    ];
});

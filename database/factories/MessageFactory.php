<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\Message::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'content' =>$faker->paragraph,
        'type' => 0,
        'user_id' => 1,
        'label' => $faker->name,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

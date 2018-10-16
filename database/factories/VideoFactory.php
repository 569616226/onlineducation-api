<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Video::class, function (Faker $faker) {
    return [
        'fileId' => '1111',
        'status' => 4,
        'name' => '5秒',
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(\App\Models\VideoUrl::class, function (Faker $faker) {
    return [
        'url' => '1111',
        'size' => 4,
        'duration' => 200,
        'video_id' => 1,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

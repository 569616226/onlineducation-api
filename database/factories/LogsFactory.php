<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Revision::class, function (Faker $faker) {
    return [
        'revisionable_type' => 'App\Models\User',
        'revisionable_id' => 1,
        'user_id' => 1,
        'key' => 'login',
        'old_value' => null,
        'new_value' =>now(),
        'created_at' => now(),
        'updated_at' => now()->addDays(1),
    ];
});

<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Spatie\Permission\Models\Role::class, function (Faker $faker) {

    return [
        'name' => $faker->slug,
        'guard_name'   => 'api',
        'display_name' => $faker->name,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

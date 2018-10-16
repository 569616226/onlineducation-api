<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\User::class, function (Faker $faker) {
    static $password;
    return [
        'name'             => $faker->name,
        'nickname'         => $faker->name,
        'password' => $password ?: $password = bcrypt('12345'),
        'frozen'           => array_random([0, 1]),
        'gender'           => array_random([0, 1, 2]),
        'remember_token'   => str_random(10),
        'created_at'       => now(),
        'updated_at'       => now()->addDays(3),
    ];
});
<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Sign::class, function (Faker $faker) {
    return [
        'name'       => $faker->name,
        'status'     => array_random([0, 1]),
        'inser_type' => array_random([0, 1]),
        'referee'    => $faker->name,
        'tel'        => $faker->phoneNumber,
        'company'    => $faker->company,
        'offer'      => $faker->name,
        'train_id'   => null,
    ];
});

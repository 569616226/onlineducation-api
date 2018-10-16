<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\models\Menu::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'icon' =>$faker->name,
        'url' => 'permissions',
        'is_nav' => 1,
        'created_at' => now(),
        'updated_at' => now()->addDays(3),
    ];
});

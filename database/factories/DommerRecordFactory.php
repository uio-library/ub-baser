<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Dommer\DommerRecord;
use Faker\Generator as Faker;

$factory->define(DommerRecord::class, function (Faker $faker) {
    return [
        'navn' => ucfirst($faker->words(5, true)),
        'aar' => $faker->year,
        'side' => $faker->numberBetween(1, 999),
    ];
});

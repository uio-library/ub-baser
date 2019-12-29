<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bases\Dommer\Record;
use Faker\Generator as Faker;

$factory->define(Record::class, function (Faker $faker) {
    return [
        'navn' => ucfirst($faker->words(5, true)),
        'aar' => $faker->year,
        'side' => $faker->numberBetween(1, 999),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Dommer\DommerKilde;
use Faker\Generator as Faker;

$factory->define(DommerKilde::class, function (Faker $faker) {
    return [
        'navn' => ucfirst($faker->words(3, true)),
    ];
});

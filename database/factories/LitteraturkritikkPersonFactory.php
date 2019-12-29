<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bases\Litteraturkritikk\Person as LitteraturkritikkPerson;
use Faker\Generator as Faker;

$factory->define(LitteraturkritikkPerson::class, function (Faker $faker) {
    return [
        'fodt' => $faker->year,
        //'dod' => $faker->year(),
        //'created_at' => $faker->un(),
        //'id' => $faker->un(),
        //'updated_at' => $faker->un(),
        //'wikidata_id' => $faker->un(),
        'etternavn' => $faker->lastName,
        'fornavn' => $faker->firstName,
        'kjonn' => $faker->randomElement(['m', 'f']),
        //'bibsys_id' => $faker->un(),
    ];
});

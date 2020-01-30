<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bases\Litteraturkritikk\Record as LitteraturkritikkRecord;
use Faker\Generator as Faker;

$factory->define(LitteraturkritikkRecord::class, function (Faker $faker) {
    return [
        'kritikktype' => $faker->words(3, false),
        'dato' => $faker->date('Y-m-d'),
        'aargang' => $faker->randomNumber(),
        'nummer' => $faker->randomNumber(),
        'bind' => $faker->randomNumber(),
        'hefte' => $faker->randomNumber(),
        'sidetall' => $faker->randomNumber(),
        'utgivelseskommentar' => $faker->sentence,
        'kommentar' => $faker->sentence(),
        'verk_fulltekst_url' => $faker->url,
        'fulltekst_url' => $faker->url,
        'spraak' => $faker->words($nb = 3, $asText = false),
        'tittel' => $faker->sentence(),
        'publikasjon' => $faker->sentence(),
        'utgivelsessted' => $faker->sentence(),
    ];
});

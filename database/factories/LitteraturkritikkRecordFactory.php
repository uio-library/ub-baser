<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bases\Litteraturkritikk\Record as LitteraturkritikkRecord;
use Faker\Generator as Faker;

$factory->define(LitteraturkritikkRecord::class, function (Faker $faker) {
    return [
        ////  'id' => $faker->(),
        // 'created_by' => $faker->undefined(),
        // 'updated_by' => $faker->undefined(),
        'kritikktype' => $faker->words(3, false),
        // 'kritiker_mfl' => $faker->undefined(),
        // 'verk_forfatter_mfl' => $faker->undefined(),
        // 'korrekturstatus' => $faker->undefined(),
        // 'tags' => $faker->undefined(),
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
        'verk_tittel' => $faker->sentence(),
        'verk_dato' => $faker->sentence(),
        'verk_sjanger' => $faker->sentence(),
        'verk_spraak' => $faker->sentence(),
        'verk_kommentar' => $faker->sentence(),
        'verk_utgivelsessted' => $faker->sentence(),
        'spraak' => $faker->sentence(),
        'tittel' => $faker->sentence(),
        'publikasjon' => $faker->sentence(),
        'utgivelsessted' => $faker->sentence(),
    ];
});

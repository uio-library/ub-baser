<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bases\Litteraturkritikk\Work as LitteraturkritikkWork;
use Faker\Generator as Faker;

$factory->define(LitteraturkritikkWork::class, function (Faker $faker) {
    return [
        'verk_tittel' => $faker->sentence(),
        'verk_originaltittel' => $faker->sentence(),
        'verk_originaltittel_transkribert' => $faker->sentence(),
        'verk_dato' => $faker->date('Y'),
        'verk_originaldato' => $faker->date('Y'),
        'verk_sjanger' => $faker->sentence(),
        'verk_spraak' => $faker->words($nb = 3, $asText = false),
        'verk_originalspraak' => $faker->words($nb = 1, $asText = false),
        'verk_kommentar' => $faker->sentence(),
        'verk_utgivelsessted' => $faker->sentence(),
        'verk_fulltekst_url' => $faker->url,
    ];
});

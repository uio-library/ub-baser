<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bases\Bibsys\BibsysDokument;
use Faker\Generator as Faker;

$divisions = [
    'UHS',
    'UBO/NETT',
    'UREAL',
    'UMED',
    'UJUR',
    'UHS/SOPH',
    'UHS/TEOL',
    'UHS/ARK',
];

$collections = [
    'Tidsskr.',
    'GSHmag316',
    'Kjem.',
    'Pensum',
    'Småtrykk',
    'Ref3',
    'Astr.',
    'Esaml.',
    'Fyssaml.',
    'SciFi',
];

$factory->define(BibsysDokument::class, function (Faker $faker) use ($divisions, $collections) {
    return [
        'dokid' => $faker->bothify('##?######'),

        'strekkode' => $faker->numerify('########'),

        'status' => $faker->sentence($nb = 3),
        'statusdato' => $faker->date(),

        'avdeling' => $faker->randomElement($divisions),
        'samling' => $faker->randomElement($collections),
        'hyllesignatur' => $faker->bothify('###.## ???'),

        'deponert' => $faker->sentence($nb = 3),
        'lokal_anmerkning' => $faker->sentence($nb = 3),
        'beholdning' => $faker->sentence($nb = 3),
        'utlaanstype' => $faker->sentence($nb = 3),
        'lisensbetingelser' => $faker->sentence($nb = 3),
        'tilleggsplassering' => $faker->sentence($nb = 3),
        'intern_bemerkning_aapen' => $faker->sentence($nb = 3),
        'bestillingstype' => $faker->lexify('??'),

        'har_hefter' => false,
    ];
});

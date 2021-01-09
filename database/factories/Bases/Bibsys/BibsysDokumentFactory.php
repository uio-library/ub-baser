<?php

namespace Database\Factories\Bases\Bibsys;

use App\Bases\Bibsys\BibsysDokument;
use Illuminate\Database\Eloquent\Factories\Factory;

class BibsysDokumentFactory extends Factory
{
    protected $divisions = [
        'UHS',
        'UBO/NETT',
        'UREAL',
        'UMED',
        'UJUR',
        'UHS/SOPH',
        'UHS/TEOL',
        'UHS/ARK',
    ];

    protected $collections = [
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

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BibsysDokument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dokid' => $this->faker->bothify('##?######'),

            'strekkode' => $this->faker->numerify('########'),

            'status' => $this->faker->sentence($nb = 3),
            'statusdato' => $this->faker->date(),

            'avdeling' => $this->faker->randomElement($this->divisions),
            'samling' => $this->faker->randomElement($this->collections),
            'hyllesignatur' => $this->faker->bothify('###.## ???'),

            'deponert' => $this->faker->sentence($nb = 3),
            'lokal_anmerkning' => $this->faker->sentence($nb = 3),
            'beholdning' => $this->faker->sentence($nb = 3),
            'utlaanstype' => $this->faker->sentence($nb = 3),
            'lisensbetingelser' => $this->faker->sentence($nb = 3),
            'tilleggsplassering' => $this->faker->sentence($nb = 3),
            'intern_bemerkning_aapen' => $this->faker->sentence($nb = 3),
            'bestillingstype' => $this->faker->lexify('??'),

            'har_hefter' => false,
        ];
    }
}

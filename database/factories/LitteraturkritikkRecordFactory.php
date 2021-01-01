<?php

namespace Database\Factories;

use App\Bases\Litteraturkritikk\Record;
use Illuminate\Database\Eloquent\Factories\Factory;

class LitteraturkritikkRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Record::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
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
            'verk_originaltittel' => $faker->sentence(),
            'verk_dato' => $faker->sentence(),
            'verk_sjanger' => $faker->sentence(),
            'verk_spraak' => $faker->words($nb = 3, $asText = false),
            'verk_originalspraak' => $faker->words($nb = 1, $asText = false),
            'verk_kommentar' => $faker->sentence(),
            'verk_utgivelsessted' => $faker->sentence(),
            'spraak' => $faker->words($nb = 3, $asText = false),
            'tittel' => $faker->sentence(),
            'publikasjon' => $faker->sentence(),
            'utgivelsessted' => $faker->sentence(),
        ];
    }
}

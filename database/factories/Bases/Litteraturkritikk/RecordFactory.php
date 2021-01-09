<?php

namespace Database\Factories\Bases\Litteraturkritikk;

use App\Bases\Litteraturkritikk\Record;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordFactory extends Factory
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
            ////  'id' => $this->>faker->(),
            // 'created_by' => $this->>faker->undefined(),
            // 'updated_by' => $this->>faker->undefined(),
            'kritikktype' => $this->faker->words(3, false),
            // 'kritiker_mfl' => $this->>faker->undefined(),
            // 'verk_forfatter_mfl' => $this->>faker->undefined(),
            // 'korrekturstatus' => $this->>faker->undefined(),
            // 'tags' => $this->>faker->undefined(),
            'dato' => $this->faker->date('Y-m-d'),
            'aargang' => $this->faker->randomNumber(),
            'nummer' => $this->faker->randomNumber(),
            'bind' => $this->faker->randomNumber(),
            'hefte' => $this->faker->randomNumber(),
            'sidetall' => $this->faker->randomNumber(),
            'utgivelseskommentar' => $this->faker->sentence,
            'kommentar' => $this->faker->sentence(),
            'verk_fulltekst_url' => $this->faker->url,
            'fulltekst_url' => $this->faker->url,
            'verk_tittel' => $this->faker->sentence(),
            'verk_originaltittel' => $this->faker->sentence(),
            'verk_dato' => $this->faker->sentence(),
            'verk_sjanger' => $this->faker->sentence(),
            'verk_spraak' => $this->faker->words($nb = 3, $asText = false),
            'verk_originalspraak' => $this->faker->words($nb = 1, $asText = false),
            'verk_kommentar' => $this->faker->sentence(),
            'verk_utgivelsessted' => $this->faker->sentence(),
            'spraak' => $this->faker->words($nb = 3, $asText = false),
            'tittel' => $this->faker->sentence(),
            'publikasjon' => $this->faker->sentence(),
            'utgivelsessted' => $this->faker->sentence(),
        ];
    }
}

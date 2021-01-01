<?php

namespace Database\Factories;

use App\Bases\Litteraturkritikk\Person as LitteraturkritikkPerson;
use Illuminate\Database\Eloquent\Factories\Factory;

class LitteraturkritikkPersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LitteraturkritikkPerson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fodt' => $this->faker->year,
            //'dod' => $this->faker->year(),
            //'created_at' => $this->faker->un(),
            //'id' => $this->faker->un(),
            //'updated_at' => $this->faker->un(),
            //'wikidata_id' => $this->faker->un(),
            'etternavn' => $this->faker->lastName,
            'fornavn' => $this->faker->firstName,
            'kjonn' => $this->faker->randomElement(['m', 'f']),
            //'bibsys_id' => $this->faker->un(),
        ];
    }
}

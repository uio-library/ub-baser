<?php

namespace Database\Factories;

use App\Bases\Dommer\Record;
use Illuminate\Database\Eloquent\Factories\Factory;

class DommerRecordFactory extends Factory
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
            'navn' => ucfirst($this->faker->words(5, true)),
            'aar' => $this->faker->year,
            'side' => $this->faker->numberBetween(1, 999),
        ];
    }
}

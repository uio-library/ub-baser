<?php

namespace Database\Factories;

use App\Bases\Dommer\DommerKilde;
use Illuminate\Database\Eloquent\Factories\Factory;

class DommerKildeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DommerKilde::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'navn' => ucfirst($this->faker->words(3, true)),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Page;
use App\Providers\AuthServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $namespace = $this->faker->randomElement(AuthServiceProvider::listGates());

        return [
            'slug' => $namespace . '/' . $this->faker->slug,
            'layout' => 'layouts.' . $namespace,
            'permission' => $namespace,
            'title' => $this->faker->title,
            'body' => str_replace("\n", "</p>\n<p>", '<p>' . $this->faker->text . '</p>'),
            'updated_by' => $this->faker->numberBetween(1, 10),
        ];
    }
}

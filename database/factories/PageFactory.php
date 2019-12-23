<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Page;
use Faker\Generator as Faker;

$factory->define(Page::class, function (Faker $faker) {
    $namespace = $faker->randomElement(\App\Providers\AuthServiceProvider::$rights);
    return [
        'slug' => $namespace . '/' . $faker->slug,
        'layout' => 'layouts.' . $namespace,
        'permission' => $namespace,
        'title' => $faker->title,
        'body' => $faker->text,
        'updated_by' => $faker->numberBetween(1,10),
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Page;
use App\Providers\AuthServiceProvider;
use Faker\Generator as Faker;

$factory->define(Page::class, function (Faker $faker) {
    $namespace = $faker->randomElement(AuthServiceProvider::listGates());
    return [
        'slug' => $namespace . '/' . $faker->slug,
        'layout' => 'layouts.' . $namespace,
        'permission' => $namespace,
        'title' => $faker->title,
        'body' => str_replace("\n", "</p>\n<p>", '<p>' . $faker->text . '</p>'),
        'updated_by' => $faker->numberBetween(1,10),
    ];
});

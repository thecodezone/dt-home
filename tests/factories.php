<?php

namespace Tests;

use Faker\Factory as Faker;

function app_factory( $params = [] ) {
    $faker = Faker::create();

    return array_merge( [
        'name' => $faker->name,
        'type' => $faker->randomElement( [ 'Webview', 'Link' ] ),
        'creation_type' => $faker->randomElement( [ 'Custom', 'Code' ] ),
        'icon' => $faker->imageUrl(),
        'url' => $faker->url,
        'sort' => $faker->numberBetween(0, 50),
        'slug' => $faker->slug,
        'is_hidden' => $faker->boolean,
    ], $params );
}

function training_factory( $params = [] ) {
    $faker = Faker::create();

    return array_merge( [
        'name' => $faker->name,
        'embed_video' => $faker->url,
        'anchor' => $faker->slug,
        'sort' => $faker->numberBetween(0, 50),
    ], $params );
}

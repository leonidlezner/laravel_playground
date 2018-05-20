<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->text(50),
        'body' => $faker->text(200),
        
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});

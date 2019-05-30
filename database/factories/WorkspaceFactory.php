<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Workspace;
use Faker\Generator as Faker;

$factory->define(Workspace::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FileManagerFolder;
use Faker\Generator as Faker;

$factory->define(FileManagerFolder::class, function (Faker $faker) {
    return [
        'id'        => $faker->randomDigit,
        'unique_id' => $faker->randomDigit,
        'user_id'   => 1,
        'parent_id' => 0,
        'name'      => $faker->sentence,
        'type'      => 'folder',
    ];
});

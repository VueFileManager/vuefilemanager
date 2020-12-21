<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FileManagerFile;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(FileManagerFile::class, function (Faker $faker) {
    return [
        'unique_id'  => $faker->randomDigit,
        'user_id'    => 0,
        'folder_id'  => 0,
        'name'       => $faker->firstName,
        'basename'   => $faker->lastName,
        'user_scope' => 'master',
        'updated_at' => Carbon::now(),
        'created_at' => Carbon::now()
    ];
});

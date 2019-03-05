<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    $firstname = $faker->firstName;
    $lastname = $faker->lastName;
    $fullName =  $firstname . ' ' . $lastname;

    return [
        'name' => $fullName,
        'username' => strtolower($firstname)[0] . strtolower($lastname),
        'department_id' => rand(1, 14),
        'role_id' => rand(2,4),
        'password' => '$2y$10$5ELydRkX3IX9GjWSjkRvoOprgXsHu.GR5.2B4VPJyJKOQY.g/v1mm', // secret
        'remember_token' => Str::random(10),
    ];
});

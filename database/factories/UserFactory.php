<?php

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

$factory->define(App\Models\Orders::class,function (Faker $faker) {
    return [
        'order_num' => str_random(8),
        'name' => $faker->name,
        'pay_type' => rand(0,1),
        'get_type' => rand(0,1),
        'if_get' => rand(0,1),
        'status' => rand(0,2),
        'pay_time' => now(),
    ];
});

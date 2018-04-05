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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt('password'), // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Article::class, function(Faker $faker) {

    $date = $faker->dateTimeThisMonth;

    return [

        'title' => $faker->sentence(),
        'content' => $faker->paragraph(),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});

$factory->define(App\Comment::class, function(Faker $faker) {

    $articleIds = App\Article::pluck('id')->toArray();
    $userIds = App\User::pluck('id')->toArray();

    return [
        'content' => $faker->paragraph,
        'commentable_type' => App\Article::class,
        'commentable_id' => function () use ($faker, $articleIds) {

            return $faker->randomElement($articleIds);
        },
        'user_id' => function () use ($faker, $userIds) {

            return $faker->randomElement($userIds);
        },
    ];
});

$factory->define(App\Vote::class, function(Faker $faker) {

    $up = $faker->randomElement([true, false]);
    $down = ! $up;
    $userIds = App\User::pluck('id')->toArray();

    return [
      'up' => $up ? 1 : null,
      'down' => $down ? 1: null,
      'user_id' => function() use ($faker, $userIds) {

        return $faker->randomElement($userIds);
      },
    ];
});
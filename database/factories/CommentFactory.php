<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
            'post_id' =>1,
            'user_id' =>function () {
                return factory(App\User::class)->create()->id;
            } ,
            'body' => $faker->text(30),

    ];
});

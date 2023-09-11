<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Empresa;
use App\Area;
use Faker\Generator as Faker;

$factory->define(Area::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name('area'),
        'empresa_id' => function(){
            return factory(Empresa::class)->create()->id;
        }
    ];
});

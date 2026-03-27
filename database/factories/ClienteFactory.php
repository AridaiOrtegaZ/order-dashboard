<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('es_MX');

        return [
            'nombre'   => $faker->name(),
            'email'    => $faker->unique()->safeEmail(),
            'telefono' => $faker->numerify('55########'),
        ];
    }
}
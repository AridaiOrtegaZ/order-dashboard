<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'fecha_entrega' => fake()->dateTimeBetween('-30 days', '+30 days'),
            'total'         => fake()->randomFloat(2, 50, 2000),
            'estado'        => fake()->randomElement(['pendiente', 'entregado', 'cancelado']),
        ];
    }
}
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake('es_MX')->randomElement([
                'Mouse inalámbrico',
                'Teclado mecánico',
                'Monitor 24 pulgadas',
                'Laptop Lenovo IdeaPad',
                'Audífonos Bluetooth',
                'Cargador USB-C 65W',
                'Mochila para laptop',
                'Disco SSD 1TB',
                'Webcam Full HD',
                'Base enfriadora para laptop',
            ]),
            'sku'    => strtoupper(fake()->unique()->bothify('PRD-####-??')),
            'precio' => fake()->randomFloat(2, 199, 4999),
        ];
    }
}
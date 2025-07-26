<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->words(2, true),        // Nombre ficticio
            'precio' => $this->faker->randomFloat(2, 5, 500), // Precio entre 5 y 500 €
            'stock' => $this->faker->numberBetween(1, 100),   // Stock entre 1 y 100 unidades
            'imagen' => null // o $this->faker->imageUrl() si se desea usar una URL
        ];
    }
}

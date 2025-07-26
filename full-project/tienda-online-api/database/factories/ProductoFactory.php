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
            'nombre' => $this->faker->words(2, true),
            'precio' => $this->faker->randomFloat(2, 5, 500),
            'stock' => $this->faker->numberBetween(1, 100),
            'imagen' => null // o puedes usar $this->faker->imageUrl() si usas URLs reales
        ];
    }
}

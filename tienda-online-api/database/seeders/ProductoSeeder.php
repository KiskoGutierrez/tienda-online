<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Camiseta Pilot',
                'precio' => 19.99,
                'stock' => 20,
                'imagen' => 'camiseta-pilot.jpg'
            ],
            [
                'nombre' => 'Sudadera Laravel',
                'precio' => 34.99,
                'stock' => 15,
                'imagen' => 'sudadera-laravel.jpg'
            ],
            [
                'nombre' => 'Taza programadora',
                'precio' => 9.99,
                'stock' => 40,
                'imagen' => 'taza-programadora.jpg'
            ],
            [
                'nombre' => 'Sticker de PHP',
                'precio' => 1.99,
                'stock' => 100,
                'imagen' => 'sticker-php.jpg'
            ],
            [
                'nombre' => 'Gorra DevMode',
                'precio' => 14.99,
                'stock' => 25,
                'imagen' => 'gorra-devmode.jpg'
            ]
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}

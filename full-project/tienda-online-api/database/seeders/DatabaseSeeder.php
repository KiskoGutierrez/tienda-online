<?php
namespace Database\Seeders;

use Database\Seeders\ProductoSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders para poblar la base de datos.
     */
    public function run(): void
    {
        // Llama al seeder ProductoSeeder para insertar datos de productos
        $this->call(ProductoSeeder::class);
    }
}

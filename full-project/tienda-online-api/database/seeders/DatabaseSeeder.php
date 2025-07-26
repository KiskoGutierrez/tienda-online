<?php
namespace Database\Seeders;

use Database\Seeders\ProductoSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ProductoSeeder::class);
    }

}

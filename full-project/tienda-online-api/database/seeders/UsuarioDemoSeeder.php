<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuarioDemoSeeder extends Seeder
{
    /**
     * Crea o actualiza un usuario administrador demo.
     */
    public function run(): void
    {
        // Busca un usuario con el email 'admin@example.com' o crea uno nuevo
        // Asigna el nombre 'Administrador' y la contraseña encriptada 'secret'
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('secret'),
            ]
        );
    }
}

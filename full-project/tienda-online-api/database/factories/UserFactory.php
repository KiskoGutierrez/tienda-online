<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * La contraseña actual que usará la fábrica (almacenada estáticamente).
     */
    protected static ?string $password;

    /**
     * Define el estado por defecto del modelo User.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(), // Genera un nombre falso
            'email' => fake()->unique()->safeEmail(), // Genera un email único y seguro
            'email_verified_at' => now(), // Marca el email como verificado
            'password' => static::$password ??= Hash::make('password'), // Usa una contraseña encriptada (la misma para todos)
            'remember_token' => Str::random(10), // Genera un token aleatorio para "remember me"
        ];
    }

    /**
     * Indica que el email del modelo no debe estar verificado.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null, // El campo queda nulo (no verificado)
        ]);
    }
}

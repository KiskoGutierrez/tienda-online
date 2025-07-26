<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase; // Resetea la base de datos en cada test para evitar conflictos

    /** @test */
    public function login_falla_con_credenciales_invalidas()
    {
        // Crea un usuario con email y contraseña conocidas
        User::factory()->create([
            'email'    => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Intenta loguearse con contraseña incorrecta y espera error 401 con mensaje específico
        $this->postJson('/api/login', [
            'email'    => 'user@example.com',
            'password' => 'wrongpassword',
        ])->assertStatus(401)
          ->assertJson(['error' => 'Credenciales inválidas']);
    }

    /** @test */
    public function usuario_puede_loguearse_con_credenciales_validas()
    {
        // Crea un usuario con email y contraseña válidos
        User::factory()->create([
            'email'    => 'user2@example.com',
            'password' => bcrypt('secret123'),
        ]);

        // Loguea correctamente y comprueba que la respuesta contenga el token y su estructura
        $response = $this->postJson('/api/login', [
            'email'    => 'user2@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'expires_in',
                 ]);
    }

    /** @test */
    public function ruta_protegida_requiere_token()
    {
        // Accede sin token a ruta protegida y espera error 401 (no autorizado)
        $this->getJson('/api/usuario')
             ->assertStatus(401);
    }

    /** @test */
    public function usuario_autenticado_accede_a_ruta_protegida()
    {
        // Crea usuario y genera token JWT
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        // Accede a ruta protegida con token y verifica respuesta con email del usuario
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/usuario')
          ->assertStatus(200)
          ->assertJsonFragment([
              'email' => $user->email,
          ]);
    }
}

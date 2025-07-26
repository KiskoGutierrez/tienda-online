<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_falla_con_credenciales_invalidas()
    {
        User::factory()->create([
            'email'    => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->postJson('/api/login', [
            'email'    => 'user@example.com',
            'password' => 'wrongpassword',
        ])->assertStatus(401)
          ->assertJson(['error' => 'Credenciales inválidas']);
    }

    /** @test */
    public function usuario_puede_loguearse_con_credenciales_validas()
    {
        User::factory()->create([
            'email'    => 'user2@example.com',
            'password' => bcrypt('secret123'),
        ]);

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
        $this->getJson('/api/usuario')
             ->assertStatus(401);
    }

    /** @test */
    public function usuario_autenticado_accede_a_ruta_protegida()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/usuario')
          ->assertStatus(200)
          ->assertJsonFragment([
              'email' => $user->email,
          ]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Producto;
use App\Models\Carrito;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarritoTest extends TestCase
{
    use RefreshDatabase; // Resetea la base de datos para cada test

    // Función privada para crear usuario y generar token JWT para autenticación
    private function crearUsuarioConToken()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@correo.com',
            'password' => Hash::make('secret123')
        ]);

        $token = JWTAuth::fromUser($user);

        return [$user, $token];
    }

    // Test para que el usuario pueda ver su carrito con autorización
    public function test_usuario_puede_ver_su_carrito()
    {
        [$user, $token] = $this->crearUsuarioConToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->getJson('/api/carrito');

        $response->assertStatus(200);
        $response->assertJsonStructure(['carrito']);
    }

    // Test para que el usuario pueda agregar un producto al carrito
    public function test_usuario_puede_agregar_producto_al_carrito()
    {
        [$user, $token] = $this->crearUsuarioConToken();

        $producto = Producto::create([
            'nombre' => 'Producto Test',
            'precio' => 100,
            'stock' => 10
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/carrito', [
            'producto_id' => $producto->id,
            'cantidad' => 2
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Producto añadido al carrito']);
    }

    // Test para que el usuario pueda eliminar un producto del carrito
    public function test_usuario_puede_eliminar_producto_del_carrito()
    {
        [$user, $token] = $this->crearUsuarioConToken();

        $producto = Producto::create([
            'nombre' => 'Producto Borrar',
            'precio' => 50,
            'stock' => 5
        ]);

        $item = Carrito::create([
            'user_id' => $user->id,
            'producto_id' => $producto->id,
            'cantidad' => 1
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson('/api/carrito/' . $item->id);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Producto eliminado del carrito']);
    }

    // Test para confirmar compra, descontar stock y limpiar carrito
    public function test_usuario_puede_confirmar_compra_y_descontar_stock()
    {
        [$user, $token] = $this->crearUsuarioConToken();

        $producto = Producto::create([
            'nombre' => 'Producto Compra',
            'precio' => 75,
            'stock' => 5
        ]);

        Carrito::create([
            'user_id' => $user->id,
            'producto_id' => $producto->id,
            'cantidad' => 2
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/compra');

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'order_id']);

        // Refresca el producto para verificar que el stock fue descontado correctamente
        $producto->refresh();
        $this->assertEquals(3, $producto->stock);

        // Verifica que el carrito quedó vacío para el usuario
        $this->assertDatabaseMissing('carritos', ['user_id' => $user->id]);
    }

    // Test para ver historial de compras de usuario
    public function test_usuario_ve_historial_de_compras()
    {
        [$user, $token] = $this->crearUsuarioConToken();

        $producto = Producto::create([
            'nombre' => 'Histórico',
            'precio' => 20,
            'stock' => 10
        ]);

        Carrito::create([
            'user_id' => $user->id,
            'producto_id' => $producto->id,
            'cantidad' => 1
        ]);

        // Primero hace una compra para generar historial
        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/compra');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/compra');

        $response->assertStatus(200);
        $response->assertJsonStructure(['historial']);
    }

    // Test para verificar que compra falla si no hay stock suficiente
    public function test_compra_falla_con_stock_insuficiente()
    {
        [$user, $token] = $this->crearUsuarioConToken();

        $producto = Producto::create([
            'nombre' => 'Sin Stock',
            'precio' => 30,
            'stock' => 1
        ]);

        Carrito::create([
            'user_id' => $user->id,
            'producto_id' => $producto->id,
            'cantidad' => 2
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/compra');

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Stock insuficiente para el producto: Sin Stock']);
    }

    // Test para verificar que compra falla si el carrito está vacío
    public function test_compra_falla_con_carrito_vacio()
    {
        [$user, $token] = $this->crearUsuarioConToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/compra');

        $response->assertStatus(400);
        $response->assertJson(['error' => 'El carrito está vacío']);
    }

    // Test para verificar que acceso a rutas protegidas falla sin token
    public function test_acceso_falla_sin_token()
    {
        $response = $this->getJson('/api/carrito');

        $response->assertStatus(401);
    }
}

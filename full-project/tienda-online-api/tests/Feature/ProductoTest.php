<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Producto;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    // Test para que el usuario vea listado de productos con paginación básica
    public function test_usuario_ve_listado_de_productos(): void
    {
        // Creamos 20 productos para probar paginación
        Producto::factory()->count(20)->create();

        $response = $this->getJson('/api/productos');
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertArrayHasKey('data', $json);
        $this->assertArrayHasKey('meta', $json);
        $this->assertArrayHasKey('links', $json);
        $this->assertGreaterThanOrEqual(10, count($json['data']));
    }

    // Test para comprobar paginación en la página 1 (máximo 10 productos)
    public function test_listado_de_productos_paginado()
    {
        Producto::factory()->count(25)->create();

        $response = $this->getJson('/api/productos?page=1');
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertArrayHasKey('data', $json);
        $this->assertArrayHasKey('meta', $json);
        $this->assertArrayHasKey('links', $json);
        $this->assertLessThanOrEqual(10, count($json['data']));
    }

    // Test para filtrar productos por nombre (search)
    public function test_filtro_de_productos_por_nombre()
    {
        Producto::create([
            'nombre' => 'Zapato de cuero',
            'precio' => 80,
            'stock' => 5
        ]);

        Producto::create([
            'nombre' => 'Camiseta blanca',
            'precio' => 20,
            'stock' => 10
        ]);

        $response = $this->getJson('/api/productos?search=zapato');
        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => 'Zapato de cuero']);
        $response->assertJsonMissing(['nombre' => 'Camiseta blanca']);
    }

    // Test para ordenar productos por precio descendente
    public function test_orden_de_productos_por_precio_descendente()
    {
        Producto::create([
            'nombre' => 'Camiseta',
            'precio' => 10,
            'stock' => 10
        ]);

        Producto::create([
            'nombre' => 'Chaqueta',
            'precio' => 100,
            'stock' => 3
        ]);

        $response = $this->getJson('/api/productos?orden=precio_desc');
        $response->assertStatus(200);

        $productos = $response->json()['data'];
        $this->assertGreaterThan(
            $productos[1]['precio'],
            $productos[0]['precio']
        );
    }
}

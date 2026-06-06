<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listar todos los productos
     */
    public function test_can_view_products_index(): void
    {
        Producto::factory()->count(3)->create();

        $response = $this->get('/productos');

        $response->assertStatus(200);
        $response->assertViewHas('productos');
    }

    /**
     * Test crear formulario de producto
     */
    public function test_can_view_create_product_form(): void
    {
        $response = $this->get('/productos/create');

        $response->assertStatus(200);
        $response->assertViewIs('productos.create');
    }

    /**
     * Test guardar nuevo producto
     */
    public function test_can_store_new_product(): void
    {
        $datos = [
            'nombre' => 'Mouse Inalámbrico',
            'descripcion' => 'Mouse wireless USB',
            'precio' => 25.99,
            'stock' => 50,
            'stock_minimo' => 10,
        ];

        $response = $this->post('/productos', $datos);

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Mouse Inalámbrico',
            'precio' => 25.99,
        ]);

        $response->assertRedirect('/productos');
    }

    /**
     * Test validación al guardar producto sin datos requeridos
     */
    public function test_product_creation_validation_fails(): void
    {
        $response = $this->post('/productos', []);

        $response->assertSessionHasErrors(['nombre', 'precio', 'stock', 'stock_minimo']);
    }

    /**
     * Test mostrar producto individual
     */
    public function test_can_view_single_product(): void
    {
        $producto = Producto::factory()->create();

        $response = $this->get("/productos/{$producto->id_producto}");

        $response->assertStatus(200);
        $response->assertViewIs('productos.show');
        $response->assertViewHas('producto', $producto);
    }

    /**
     * Test editar producto
     */
    public function test_can_view_edit_product_form(): void
    {
        $producto = Producto::factory()->create();

        $response = $this->get("/productos/{$producto->id_producto}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('productos.edit');
        $response->assertViewHas('producto', $producto);
    }

    /**
     * Test actualizar producto
     */
    public function test_can_update_product(): void
    {
        $producto = Producto::factory()->create([
            'nombre' => 'Producto Original',
            'precio' => 100,
        ]);

        $response = $this->put("/productos/{$producto->id_producto}", [
            'nombre' => 'Producto Actualizado',
            'precio' => 150,
            'stock' => 20,
            'stock_minimo' => 5,
            'descripcion' => 'Descripción actualizada',
        ]);

        $this->assertDatabaseHas('productos', [
            'id_producto' => $producto->id_producto,
            'nombre' => 'Producto Actualizado',
            'precio' => 150,
        ]);

        $response->assertRedirect('/productos');
    }

    /**
     * Test eliminar producto
     */
    public function test_can_delete_product(): void
    {
        $producto = Producto::factory()->create();

        $response = $this->delete("/productos/{$producto->id_producto}");

        $this->assertDatabaseMissing('productos', [
            'id_producto' => $producto->id_producto,
        ]);

        $response->assertRedirect('/productos');
    }

    /**
     * Test error 404 cuando producto no existe
     */
    public function test_product_not_found_returns_404(): void
    {
        $response = $this->get('/productos/9999');

        $response->assertStatus(404);
    }
}

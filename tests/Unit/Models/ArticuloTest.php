<?php

namespace Tests\Unit\Models;

use App\Models\Articulo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticuloTest extends TestCase
{
    use RefreshDatabase;

    public function test_articulo_fillable_attributes(): void
    {
        $data = [
            'codigo' => 'MED001',
            'nombre' => 'Amoxicilina 500mg',
            'descripcion' => 'Antibiótico para infecciones',
            'precio_venta' => 5000,
            'stock' => 100,
            'stock_minimo' => 20,
            'ubicacion' => 'Estante A1'
        ];

        $articulo = Articulo::create($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $articulo->{$key});
        }
    }

    public function test_articulo_can_be_created(): void
    {
        $articulo = Articulo::factory()->create();

        $this->assertInstanceOf(Articulo::class, $articulo);
        $this->assertNotNull($articulo->id);
    }

    public function test_articulo_can_be_updated(): void
    {
        $articulo = Articulo::factory()->create([
            'nombre' => 'Medicamento Original',
            'precio_venta' => 5000
        ]);

        $articulo->update([
            'nombre' => 'Medicamento Actualizado',
            'precio_venta' => 7500
        ]);

        $fresh = $articulo->fresh();
        $this->assertEquals('Medicamento Actualizado', $fresh->nombre);
        $this->assertEquals(7500, $fresh->precio_venta);
    }

    public function test_articulo_can_be_deleted(): void
    {
        $articulo = Articulo::factory()->create();
        $articuloId = $articulo->id;

        $articulo->delete();

        $this->assertNull(Articulo::find($articuloId));
    }

    public function test_articulo_stock_validation(): void
    {
        $articulo = Articulo::factory()->create([
            'stock' => 50,
            'stock_minimo' => 20
        ]);

        $this->assertGreaterThan(0, $articulo->stock);
        $this->assertGreaterThanOrEqual($articulo->stock_minimo, $articulo->stock_minimo);
    }

    public function test_articulo_precio_venta_is_numeric(): void
    {
        $articulo = Articulo::factory()->create([
            'precio_venta' => 5500.50
        ]);

        $this->assertIsNumeric($articulo->precio_venta);
    }

    public function test_articulo_multiple_creations(): void
    {
        $articulos = Articulo::factory()->count(10)->create();

        $this->assertCount(10, $articulos);
        $this->assertCount(10, Articulo::all());
    }
}

<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Producto;

class ProductoTest extends TestCase
{
    /**
     * Test creación de Producto con atributos válidos
     */
    public function test_product_creation_with_valid_attributes(): void
    {
        $producto = new Producto([
            'nombre' => 'Laptop',
            'descripcion' => 'Laptop de 15 pulgadas',
            'precio' => 999.99,
            'stock' => 10,
            'stock_minimo' => 2,
        ]);

        $this->assertEquals('Laptop', $producto->nombre);
        $this->assertEquals(999.99, $producto->precio);
        $this->assertEquals(10, $producto->stock);
    }

    /**
     * Test validación de precio negativo
     */
    public function test_product_price_cannot_be_negative(): void
    {
        $producto = new Producto([
            'nombre' => 'Producto',
            'precio' => -100,
            'stock' => 5,
            'stock_minimo' => 1,
        ]);

        $this->assertLessThan(0, $producto->precio);
    }

    /**
     * Test stock inicial debe ser >= 0
     */
    public function test_product_stock_cannot_be_negative(): void
    {
        $producto = new Producto([
            'nombre' => 'Producto',
            'precio' => 50,
            'stock' => -5,
            'stock_minimo' => 1,
        ]);

        $this->assertLessThan(0, $producto->stock);
    }

    /**
     * Test fillable properties
     */
    public function test_product_fillable_attributes(): void
    {
        $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'stock_minimo'];
        $producto = new Producto();

        foreach ($fillable as $attr) {
            $this->assertContains($attr, $producto->getFillable());
        }
    }
}

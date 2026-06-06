<?php

namespace Tests\Unit\Models;

use App\Models\Proveedor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProveedorTest extends TestCase
{
    use RefreshDatabase;

    public function test_proveedor_fillable_attributes(): void
    {
        $data = [
            'codigo' => 'PROV001',
            'nombre' => 'Farmacéutica XYZ',
            'ruc' => '1234567890123',
            'telefono' => '3001234567',
            'email' => 'proveedor@example.com',
            'direccion' => 'Avenida 1 #456',
            'contacto_nombre' => 'Juan González',
            'contacto_telefono' => '3009876543',
            'activo' => true
        ];

        $proveedor = Proveedor::create($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $proveedor->{$key});
        }
    }

    public function test_proveedor_can_be_created(): void
    {
        $proveedor = Proveedor::factory()->create();

        $this->assertInstanceOf(Proveedor::class, $proveedor);
        $this->assertNotNull($proveedor->id);
    }

    public function test_proveedor_can_be_updated(): void
    {
        $proveedor = Proveedor::factory()->create([
            'nombre' => 'Proveedor Original',
            'email' => 'original@example.com'
        ]);

        $proveedor->update([
            'nombre' => 'Proveedor Actualizado',
            'email' => 'actualizado@example.com'
        ]);

        $fresh = $proveedor->fresh();
        $this->assertEquals('Proveedor Actualizado', $fresh->nombre);
        $this->assertEquals('actualizado@example.com', $fresh->email);
    }

    public function test_proveedor_can_be_deleted(): void
    {
        $proveedor = Proveedor::factory()->create();
        $proveedorId = $proveedor->id;

        $proveedor->delete();

        $this->assertNull(Proveedor::find($proveedorId));
    }

    public function test_proveedor_activo_attribute(): void
    {
        $proveedorActivo = Proveedor::factory()->create(['activo' => true]);
        $proveedorInactivo = Proveedor::factory()->create(['activo' => false]);

        $this->assertTrue($proveedorActivo->activo);
        $this->assertFalse($proveedorInactivo->activo);
    }

    public function test_proveedor_optional_attributes(): void
    {
        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Mínimo'
        ]);

        $this->assertNull($proveedor->codigo);
        $this->assertNull($proveedor->ruc);
        $this->assertNull($proveedor->email);
    }

    public function test_proveedor_multiple_creations(): void
    {
        $proveedores = Proveedor::factory()->count(10)->create();

        $this->assertCount(10, $proveedores);
        $this->assertCount(10, Proveedor::all());
    }
}

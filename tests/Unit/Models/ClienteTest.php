<?php

namespace Tests\Unit\Models;

use App\Models\Cliente;
use App\Models\Articulo;
use App\Models\Proveedor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_fillable_attributes(): void
    {
        $data = [
            'codigo' => 'CLI001',
            'nombre' => 'Cliente Test',
            'cedula' => '1234567890',
            'telefono' => '3001234567',
            'email' => 'cliente@test.com',
            'direccion' => 'Calle 1 #123',
            'activo' => true
        ];

        $cliente = Cliente::create($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $cliente->{$key});
        }
    }

    public function test_cliente_can_be_created(): void
    {
        $cliente = Cliente::factory()->create();

        $this->assertInstanceOf(Cliente::class, $cliente);
        $this->assertNotNull($cliente->id);
    }

    public function test_cliente_can_be_updated(): void
    {
        $cliente = Cliente::factory()->create(['nombre' => 'Cliente Original']);

        $cliente->update(['nombre' => 'Cliente Actualizado']);

        $this->assertEquals('Cliente Actualizado', $cliente->fresh()->nombre);
    }

    public function test_cliente_can_be_deleted(): void
    {
        $cliente = Cliente::factory()->create();
        $clienteId = $cliente->id;

        $cliente->delete();

        $this->assertNull(Cliente::find($clienteId));
    }
}

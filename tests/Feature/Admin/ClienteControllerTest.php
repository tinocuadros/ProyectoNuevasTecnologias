<?php

namespace Tests\Feature\Admin;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_returns_all_clientes(): void
    {
        Cliente::factory()->count(15)->create();

        $response = $this->actingAs($this->user)->getJson('/api/admin/clientes');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'meta']);
    }

    public function test_index_filters_by_codigo(): void
    {
        $cliente1 = Cliente::factory()->create(['codigo' => 'CLI001']);
        $cliente2 = Cliente::factory()->create(['codigo' => 'CLI002']);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/clientes?codigo=CLI001');

        $response->assertStatus(200);
    }

    public function test_index_filters_by_nombre(): void
    {
        $cliente = Cliente::factory()->create(['nombre' => 'Juan Pérez']);
        Cliente::factory()->create(['nombre' => 'María García']);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/clientes?nombre=Juan');

        $response->assertStatus(200);
    }

    public function test_store_creates_new_cliente(): void
    {
        $data = [
            'codigo' => 'CLI001',
            'nombre' => 'Nuevo Cliente',
            'cedula' => '1234567890',
            'telefono' => '3001234567',
            'email' => 'cliente@example.com',
            'direccion' => 'Calle 1 #123',
            'activo' => true
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/clientes', $data);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseHas('clientes', [
            'nombre' => 'Nuevo Cliente',
            'email' => 'cliente@example.com'
        ]);
    }

    public function test_store_validates_required_nombre(): void
    {
        $data = ['codigo' => 'CLI001'];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/clientes', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nombre');
    }

    public function test_store_validates_unique_nombre(): void
    {
        Cliente::factory()->create(['nombre' => 'Cliente Duplicado']);

        $data = [
            'nombre' => 'Cliente Duplicado',
            'cedula' => '1111111111',
            'telefono' => '3001234567'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/clientes', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nombre');
    }

    public function test_store_validates_email_format(): void
    {
        $data = [
            'nombre' => 'Nuevo Cliente',
            'email' => 'email-invalido',
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/clientes', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_show_returns_cliente(): void
    {
        $cliente = Cliente::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson("/api/admin/clientes/{$cliente->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('cliente.id', $cliente->id);
    }

    public function test_show_returns_404_when_not_found(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/clientes/99999');

        $response->assertStatus(404);
        $response->assertJsonPath('success', false);
    }

    public function test_update_modifies_cliente(): void
    {
        $cliente = Cliente::factory()->create();

        $data = [
            'nombre' => 'Cliente Actualizado',
            'telefono' => '3009999999'
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/admin/clientes/{$cliente->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'nombre' => 'Cliente Actualizado'
        ]);
    }

    public function test_destroy_deletes_cliente(): void
    {
        $cliente = Cliente::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/admin/clientes/{$cliente->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertModelMissing($cliente);
    }
}

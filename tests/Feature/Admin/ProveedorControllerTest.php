<?php

namespace Tests\Feature\Admin;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProveedorControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_returns_all_proveedores(): void
    {
        Proveedor::factory()->count(15)->create();

        $response = $this->actingAs($this->user)->getJson('/api/admin/proveedores');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'meta']);
    }

    public function test_index_filters_by_codigo(): void
    {
        $proveedor1 = Proveedor::factory()->create(['codigo' => 'PROV001']);
        $proveedor2 = Proveedor::factory()->create(['codigo' => 'PROV002']);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/proveedores?codigo=PROV001');

        $response->assertStatus(200);
    }

    public function test_index_filters_by_nombre(): void
    {
        $proveedor = Proveedor::factory()->create(['nombre' => 'Farmacéutica XYZ']);
        Proveedor::factory()->create(['nombre' => 'Farmacéutica ABC']);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/proveedores?nombre=XYZ');

        $response->assertStatus(200);
    }

    public function test_index_filters_by_ruc(): void
    {
        $proveedor = Proveedor::factory()->create(['ruc' => '1234567890123']);
        Proveedor::factory()->create(['ruc' => '9876543210123']);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/proveedores?ruc=1234567890123');

        $response->assertStatus(200);
    }

    public function test_index_filters_by_activo(): void
    {
        Proveedor::factory()->create(['activo' => true]);
        Proveedor::factory()->create(['activo' => false]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/proveedores?activo=1');

        $response->assertStatus(200);
    }

    public function test_store_creates_new_proveedor(): void
    {
        $data = [
            'codigo' => 'PROV001',
            'nombre' => 'Farmacéutica Nueva',
            'ruc' => '1234567890123',
            'telefono' => '3001234567',
            'email' => 'proveedor@example.com',
            'direccion' => 'Avenida 1 #456',
            'contacto_nombre' => 'Juan González',
            'contacto_telefono' => '3009876543',
            'activo' => true
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/proveedores', $data);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseHas('proveedores', [
            'nombre' => 'Farmacéutica Nueva',
            'email' => 'proveedor@example.com'
        ]);
    }

    public function test_store_validates_required_nombre(): void
    {
        $data = [
            'codigo' => 'PROV001',
            'ruc' => '1234567890123'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/proveedores', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nombre');
    }

    public function test_store_validates_unique_nombre(): void
    {
        Proveedor::factory()->create(['nombre' => 'Farmacéutica Duplicada']);

        $data = [
            'nombre' => 'Farmacéutica Duplicada',
            'ruc' => '1111111111111',
            'telefono' => '3001234567'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/proveedores', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nombre');
    }

    public function test_store_validates_unique_ruc(): void
    {
        Proveedor::factory()->create(['ruc' => '1234567890123']);

        $data = [
            'nombre' => 'Otro Proveedor',
            'ruc' => '1234567890123'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/proveedores', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('ruc');
    }

    public function test_store_validates_email_format(): void
    {
        $data = [
            'nombre' => 'Nuevo Proveedor',
            'email' => 'email-invalido',
            'telefono' => '3001234567'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/proveedores', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_show_returns_proveedor(): void
    {
        $proveedor = Proveedor::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson("/api/admin/proveedores/{$proveedor->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('proveedor.id', $proveedor->id);
    }

    public function test_show_returns_404_when_not_found(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/proveedores/99999');

        $response->assertStatus(404);
        $response->assertJsonPath('success', false);
    }

    public function test_update_modifies_proveedor(): void
    {
        $proveedor = Proveedor::factory()->create();

        $data = [
            'nombre' => 'Proveedor Actualizado',
            'telefono' => '3009999999',
            'email' => 'actualizado@example.com'
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/admin/proveedores/{$proveedor->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseHas('proveedores', [
            'id' => $proveedor->id,
            'nombre' => 'Proveedor Actualizado'
        ]);
    }

    public function test_destroy_deletes_proveedor(): void
    {
        $proveedor = Proveedor::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/admin/proveedores/{$proveedor->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertModelMissing($proveedor);
    }
}

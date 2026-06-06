<?php

namespace Tests\Feature\Admin;

use App\Models\Articulo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticuloControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_returns_all_articulos(): void
    {
        Articulo::factory()->count(15)->create();

        $response = $this->actingAs($this->user)->getJson('/api/admin/articulos');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'meta']);
    }

    public function test_index_filters_by_codigo(): void
    {
        $articulo1 = Articulo::factory()->create(['codigo' => 'ART001']);
        $articulo2 = Articulo::factory()->create(['codigo' => 'ART002']);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/articulos?codigo=ART001');

        $response->assertStatus(200);
    }

    public function test_index_filters_by_nombre(): void
    {
        $articulo = Articulo::factory()->create(['nombre' => 'Medicamento A']);
        Articulo::factory()->create(['nombre' => 'Medicamento B']);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/articulos?nombre=Medicamento A');

        $response->assertStatus(200);
    }

    public function test_index_filters_by_stock_bajo(): void
    {
        Articulo::factory()->create([
            'stock' => 5,
            'stock_minimo' => 10
        ]);
        Articulo::factory()->create([
            'stock' => 15,
            'stock_minimo' => 10
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/articulos?stock=bajo');

        $response->assertStatus(200);
    }

    public function test_index_filters_by_stock_agotado(): void
    {
        Articulo::factory()->create(['stock' => 0]);
        Articulo::factory()->create(['stock' => 5]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/articulos?stock=agotado');

        $response->assertStatus(200);
    }

    public function test_store_creates_new_articulo(): void
    {
        $data = [
            'codigo' => 'MED001',
            'nombre' => 'Amoxicilina 500mg',
            'descripcion' => 'Antibiótico',
            'precio_venta' => 5000,
            'stock' => 100,
            'stock_minimo' => 20,
            'ubicacion' => 'Estante A1'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/articulos', $data);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseHas('articulos', [
            'nombre' => 'Amoxicilina 500mg',
            'precio_venta' => 5000
        ]);
    }

    public function test_store_validates_required_nombre(): void
    {
        $data = [
            'codigo' => 'MED001',
            'precio_venta' => 5000,
            'stock' => 100,
            'stock_minimo' => 20
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/articulos', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nombre');
    }

    public function test_store_validates_required_precio_venta(): void
    {
        $data = [
            'codigo' => 'MED001',
            'nombre' => 'Amoxicilina 500mg',
            'stock' => 100,
            'stock_minimo' => 20
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/articulos', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('precio_venta');
    }

    public function test_store_validates_numeric_precio_venta(): void
    {
        $data = [
            'nombre' => 'Amoxicilina 500mg',
            'precio_venta' => 'no-numerico',
            'stock' => 100,
            'stock_minimo' => 20
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/articulos', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('precio_venta');
    }

    public function test_store_validates_stock_min_zero(): void
    {
        $data = [
            'nombre' => 'Amoxicilina 500mg',
            'precio_venta' => 5000,
            'stock' => -1,
            'stock_minimo' => 20
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/articulos', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('stock');
    }

    public function test_show_returns_articulo(): void
    {
        $articulo = Articulo::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson("/api/admin/articulos/{$articulo->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('articulo.id', $articulo->id);
    }

    public function test_show_returns_404_when_not_found(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/articulos/99999');

        $response->assertStatus(404);
        $response->assertJsonPath('success', false);
    }

    public function test_update_modifies_articulo(): void
    {
        $articulo = Articulo::factory()->create();

        $data = [
            'nombre' => 'Articulo Actualizado',
            'precio_venta' => 7500,
            'stock' => 50,
            'stock_minimo' => 15
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/admin/articulos/{$articulo->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseHas('articulos', [
            'id' => $articulo->id,
            'nombre' => 'Articulo Actualizado',
            'precio_venta' => 7500
        ]);
    }

    public function test_destroy_deletes_articulo(): void
    {
        $articulo = Articulo::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/admin/articulos/{$articulo->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertModelMissing($articulo);
    }
}

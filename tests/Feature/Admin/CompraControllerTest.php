<?php

namespace Tests\Feature\Admin;

use App\Models\Articulo;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Lote;
use App\Models\MovimientoInventario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompraControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Proveedor $proveedor;
    protected Articulo $articulo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->proveedor = Proveedor::factory()->create();
        $this->articulo = Articulo::factory()->create();
    }

    public function test_index_returns_all_compras(): void
    {
        Compra::factory()->count(5)->create(['proveedor_id' => $this->proveedor->id]);

        $response = $this->actingAs($this->user)->getJson('/api/admin/compras');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'meta']);
    }

    public function test_index_filters_by_numero_factura(): void
    {
        $compra = Compra::factory()->create([
            'numero_factura' => 'FAC001',
            'proveedor_id' => $this->proveedor->id
        ]);
        Compra::factory()->create([
            'numero_factura' => 'FAC002',
            'proveedor_id' => $this->proveedor->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/compras?numero_factura=FAC001');

        $response->assertStatus(200);
    }

    public function test_index_filters_by_proveedor_id(): void
    {
        $proveedor2 = Proveedor::factory()->create();
        Compra::factory()->create(['proveedor_id' => $this->proveedor->id]);
        Compra::factory()->create(['proveedor_id' => $proveedor2->id]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/admin/compras?proveedor_id={$this->proveedor->id}");

        $response->assertStatus(200);
    }

    public function test_index_filters_by_fecha_rango(): void
    {
        Compra::factory()->create([
            'fecha' => '2026-01-15',
            'proveedor_id' => $this->proveedor->id
        ]);
        Compra::factory()->create([
            'fecha' => '2026-06-01',
            'proveedor_id' => $this->proveedor->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/compras?fecha_inicio=2026-01-01&fecha_fin=2026-03-31');

        $response->assertStatus(200);
    }

    public function test_store_creates_compra_with_detalles(): void
    {
        $data = [
            'numero_factura' => 'FAC001',
            'proveedor_id' => $this->proveedor->id,
            'fecha' => '2026-06-06',
            'observaciones' => 'Primera compra',
            'detalles' => [
                [
                    'articulo_id' => $this->articulo->id,
                    'cantidad' => 50,
                    'precio_unitario' => 100
                ]
            ]
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/compras', $data);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseHas('compras', [
            'numero_factura' => 'FAC001',
            'total' => 5000
        ]);
    }

    public function test_store_updates_articulo_stock(): void
    {
        $stockAnterior = $this->articulo->stock;

        $data = [
            'numero_factura' => 'FAC001',
            'proveedor_id' => $this->proveedor->id,
            'fecha' => '2026-06-06',
            'detalles' => [
                [
                    'articulo_id' => $this->articulo->id,
                    'cantidad' => 50,
                    'precio_unitario' => 100
                ]
            ]
        ];

        $this->actingAs($this->user)->postJson('/api/admin/compras', $data);

        $this->articulo->refresh();
        $this->assertEquals($stockAnterior + 50, $this->articulo->stock);
    }

    public function test_store_creates_lote(): void
    {
        $data = [
            'numero_factura' => 'FAC001',
            'proveedor_id' => $this->proveedor->id,
            'fecha' => '2026-06-06',
            'detalles' => [
                [
                    'articulo_id' => $this->articulo->id,
                    'cantidad' => 50,
                    'precio_unitario' => 100
                ]
            ]
        ];

        $this->actingAs($this->user)->postJson('/api/admin/compras', $data);

        $this->assertDatabaseHas('lotes', [
            'articulo_id' => $this->articulo->id,
            'cantidad' => 50,
            'cantidad_disponible' => 50,
            'costo_unitario' => 100
        ]);
    }

    public function test_store_creates_movimiento_inventario(): void
    {
        $data = [
            'numero_factura' => 'FAC001',
            'proveedor_id' => $this->proveedor->id,
            'fecha' => '2026-06-06',
            'detalles' => [
                [
                    'articulo_id' => $this->articulo->id,
                    'cantidad' => 50,
                    'precio_unitario' => 100
                ]
            ]
        ];

        $this->actingAs($this->user)->postJson('/api/admin/compras', $data);

        $this->assertDatabaseHas('movimiento_inventarios', [
            'articulo_id' => $this->articulo->id,
            'tipo' => 'entrada',
            'cantidad' => 50
        ]);
    }

    public function test_store_validates_required_proveedor_id(): void
    {
        $data = [
            'numero_factura' => 'FAC001',
            'fecha' => '2026-06-06',
            'detalles' => [[
                'articulo_id' => $this->articulo->id,
                'cantidad' => 50,
                'precio_unitario' => 100
            ]]
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/compras', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('proveedor_id');
    }

    public function test_store_validates_required_detalles(): void
    {
        $data = [
            'numero_factura' => 'FAC001',
            'proveedor_id' => $this->proveedor->id,
            'fecha' => '2026-06-06',
            'detalles' => []
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/compras', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('detalles');
    }

    public function test_store_validates_multiple_detalles(): void
    {
        $articulo2 = Articulo::factory()->create();
        
        $data = [
            'numero_factura' => 'FAC001',
            'proveedor_id' => $this->proveedor->id,
            'fecha' => '2026-06-06',
            'detalles' => [
                [
                    'articulo_id' => $this->articulo->id,
                    'cantidad' => 50,
                    'precio_unitario' => 100
                ],
                [
                    'articulo_id' => $articulo2->id,
                    'cantidad' => 30,
                    'precio_unitario' => 200
                ]
            ]
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/compras', $data);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_show_returns_compra_with_detalles(): void
    {
        $compra = Compra::factory()->create(['proveedor_id' => $this->proveedor->id]);
        DetalleCompra::factory()->create(['compra_id' => $compra->id]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/admin/compras/{$compra->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('compra.id', $compra->id);
        $response->assertJsonStructure(['compra' => ['detalles']]);
    }

    public function test_show_returns_404_when_not_found(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/admin/compras/99999');

        $response->assertStatus(404);
        $response->assertJsonPath('success', false);
    }

    public function test_destroy_reverts_stock(): void
    {
        $data = [
            'numero_factura' => 'FAC001',
            'proveedor_id' => $this->proveedor->id,
            'fecha' => '2026-06-06',
            'detalles' => [[
                'articulo_id' => $this->articulo->id,
                'cantidad' => 50,
                'precio_unitario' => 100
            ]]
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/compras', $data);

        $compraData = $response->json();
        $compraId = $compraData['compra']['id'];
        $stockConCompra = $this->articulo->fresh()->stock;

        $this->actingAs($this->user)
            ->deleteJson("/api/admin/compras/{$compraId}");

        $this->articulo->refresh();
        $this->assertEquals($stockConCompra - 50, $this->articulo->stock);
    }

    public function test_destroy_deletes_lotes(): void
    {
        $data = [
            'numero_factura' => 'FAC001',
            'proveedor_id' => $this->proveedor->id,
            'fecha' => '2026-06-06',
            'detalles' => [[
                'articulo_id' => $this->articulo->id,
                'cantidad' => 50,
                'precio_unitario' => 100
            ]]
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/admin/compras', $data);

        $compraData = $response->json();
        $compraId = $compraData['compra']['id'];

        $this->assertDatabaseHas('lotes', ['compra_id' => $compraId]);

        $this->actingAs($this->user)
            ->deleteJson("/api/admin/compras/{$compraId}");

        $this->assertDatabaseMissing('lotes', ['compra_id' => $compraId]);
    }

    public function test_destroy_returns_404_when_not_found(): void
    {
        $response = $this->actingAs($this->user)
            ->deleteJson('/api/admin/compras/99999');

        $response->assertStatus(404);
        $response->assertJsonPath('success', false);
    }
}

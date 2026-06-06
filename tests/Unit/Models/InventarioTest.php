<?php

namespace Tests\Unit\Models;

use App\Models\Articulo;
use App\Models\Lote;
use App\Models\MovimientoInventario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventarioTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Articulo $articulo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->articulo = Articulo::factory()->create([
            'stock' => 0,
            'stock_minimo' => 10
        ]);
    }

    public function test_lote_stores_entrada_inventory(): void
    {
        $lote = Lote::create([
            'articulo_id' => $this->articulo->id,
            'cantidad' => 100,
            'cantidad_disponible' => 100,
            'costo_unitario' => 50,
            'fecha_entrada' => now(),
            'activo' => true
        ]);

        $this->assertDatabaseHas('lotes', [
            'articulo_id' => $this->articulo->id,
            'cantidad' => 100,
            'cantidad_disponible' => 100
        ]);
    }

    public function test_movimiento_inventario_entrada(): void
    {
        $movimiento = MovimientoInventario::create([
            'articulo_id' => $this->articulo->id,
            'tipo' => 'entrada',
            'cantidad' => 50,
            'stock_anterior' => 0,
            'stock_nuevo' => 50,
            'motivo' => 'Compra inicial',
            'user_id' => $this->user->id
        ]);

        $this->assertDatabaseHas('movimiento_inventarios', [
            'articulo_id' => $this->articulo->id,
            'tipo' => 'entrada',
            'cantidad' => 50,
            'stock_nuevo' => 50
        ]);
    }

    public function test_movimiento_inventario_salida(): void
    {
        $this->articulo->update(['stock' => 100]);

        $movimiento = MovimientoInventario::create([
            'articulo_id' => $this->articulo->id,
            'tipo' => 'salida',
            'cantidad' => 30,
            'stock_anterior' => 100,
            'stock_nuevo' => 70,
            'motivo' => 'Venta',
            'user_id' => $this->user->id
        ]);

        $this->assertDatabaseHas('movimiento_inventarios', [
            'tipo' => 'salida',
            'cantidad' => 30,
            'stock_nuevo' => 70
        ]);
    }

    public function test_articulo_is_low_stock(): void
    {
        $articulo = Articulo::factory()->create([
            'stock' => 5,
            'stock_minimo' => 10
        ]);

        $this->assertTrue($articulo->stock <= $articulo->stock_minimo);
    }

    public function test_articulo_is_out_of_stock(): void
    {
        $articulo = Articulo::factory()->create([
            'stock' => 0,
            'stock_minimo' => 10
        ]);

        $this->assertTrue($articulo->stock === 0);
    }

    public function test_articulo_is_normal_stock(): void
    {
        $articulo = Articulo::factory()->create([
            'stock' => 50,
            'stock_minimo' => 10
        ]);

        $this->assertTrue($articulo->stock > $articulo->stock_minimo);
    }

    public function test_movimiento_inventario_records_user(): void
    {
        $movimiento = MovimientoInventario::create([
            'articulo_id' => $this->articulo->id,
            'tipo' => 'entrada',
            'cantidad' => 50,
            'stock_anterior' => 0,
            'stock_nuevo' => 50,
            'motivo' => 'Compra',
            'user_id' => $this->user->id
        ]);

        $this->assertEquals($this->user->id, $movimiento->user_id);
    }

    public function test_lote_peps_method(): void
    {
        $lote1 = Lote::create([
            'articulo_id' => $this->articulo->id,
            'cantidad' => 100,
            'cantidad_disponible' => 100,
            'costo_unitario' => 50,
            'fecha_entrada' => now()->subDays(5),
            'activo' => true
        ]);

        $lote2 = Lote::create([
            'articulo_id' => $this->articulo->id,
            'cantidad' => 100,
            'cantidad_disponible' => 100,
            'costo_unitario' => 60,
            'fecha_entrada' => now(),
            'activo' => true
        ]);

        $lotesOrdenados = Lote::where('articulo_id', $this->articulo->id)
            ->orderBy('fecha_entrada', 'asc')
            ->get();

        $this->assertEquals($lote1->id, $lotesOrdenados->first()->id);
        $this->assertEquals($lote2->id, $lotesOrdenados->last()->id);
    }

    public function test_movimiento_inventario_timestamp(): void
    {
        $movimiento = MovimientoInventario::create([
            'articulo_id' => $this->articulo->id,
            'tipo' => 'entrada',
            'cantidad' => 50,
            'stock_anterior' => 0,
            'stock_nuevo' => 50,
            'motivo' => 'Compra',
            'user_id' => $this->user->id
        ]);

        $this->assertNotNull($movimiento->created_at);
        $this->assertInstanceOf(\DateTime::class, $movimiento->created_at);
    }

    public function test_multiple_movimientos_for_same_articulo(): void
    {
        for ($i = 0; $i < 5; $i++) {
            MovimientoInventario::create([
                'articulo_id' => $this->articulo->id,
                'tipo' => $i % 2 === 0 ? 'entrada' : 'salida',
                'cantidad' => 10,
                'stock_anterior' => $i * 10,
                'stock_nuevo' => ($i + 1) * 10,
                'motivo' => "Movimiento {$i}",
                'user_id' => $this->user->id
            ]);
        }

        $movimientos = MovimientoInventario::where('articulo_id', $this->articulo->id)->get();
        $this->assertCount(5, $movimientos);
    }
}

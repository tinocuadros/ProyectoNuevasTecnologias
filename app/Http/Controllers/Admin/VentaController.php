<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Articulo;
use App\Models\Lote;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;


class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with(['cliente', 'usuario']);
        
        if ($request->numero_factura) {
            $query->where('numero_factura', 'like', '%' . $request->numero_factura . '%');
        }
        if ($request->cliente_id) {
            $query->where('cliente_id', $request->cliente_id);
        }
        if ($request->fecha_inicio && $request->fecha_fin) {
            $query->whereBetween('fecha_venta', [$request->fecha_inicio, $request->fecha_fin]);
        }
        
        $ventas = $query->orderBy('fecha_venta', 'desc')->paginate(10);
        return response()->json($ventas);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'required|date',
            'detalles' => 'required|array|min:1',
            'detalles.*.articulo_id' => 'required|exists:articulos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            $venta = Venta::create([
                'cliente_id' => $data['cliente_id'],
                'user_id' => auth()->id(),
                'fecha_venta' => $data['fecha_venta'],
                'total' => 0,
                'estado' => 'completada'
            ]);

            $total = 0;
            foreach ($data['detalles'] as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                
                // Verificar stock disponible
                $articulo = Articulo::find($detalle['articulo_id']);
                if ($articulo->stock < $detalle['cantidad']) {
                    throw new \Exception('Stock insuficiente para ' . $articulo->nombre);
                }

                // Crear detalle de venta
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'articulo_id' => $detalle['articulo_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $subtotal
                ]);
                
                $total += $subtotal;

                // Salida de inventario usando PEPS (lotes más antiguos)
                $cantidadRestante = $detalle['cantidad'];
                $lotes = Lote::where('articulo_id', $detalle['articulo_id'])
                    ->where('activo', true)
                    ->where('cantidad_disponible', '>', 0)
                    ->orderBy('fecha_entrada', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                foreach ($lotes as $lote) {
                    if ($cantidadRestante <= 0) break;

                    $cantidadASacar = min($lote->cantidad_disponible, $cantidadRestante);
                    
                    $stockAnterior = $articulo->stock;
                    $stockNuevo = $stockAnterior - $cantidadASacar;
                    $articulo->update(['stock' => $stockNuevo]);

                    $lote->cantidad_disponible -= $cantidadASacar;
                    if ($lote->cantidad_disponible == 0) {
                        $lote->activo = false;
                    }
                    $lote->save();

                    MovimientoInventario::create([
                        'articulo_id' => $detalle['articulo_id'],
                        'tipo' => 'salida',
                        'cantidad' => $cantidadASacar,
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockNuevo,
                        'motivo' => 'Venta #' . $venta->id,
                        'documento_id' => $venta->id,
                        'documento_type' => Venta::class,
                        'user_id' => auth()->id()
                    ]);

                    $cantidadRestante -= $cantidadASacar;
                }
            }

            // Generar número de factura
            $numeroFactura = 'FAC-' . str_pad($venta->id, 6, '0', STR_PAD_LEFT);
            $venta->update(['total' => $total, 'numero_factura' => $numeroFactura]);
            
            DB::commit();

            return response()->json(['success' => true, 'venta' => $venta->load(['detalles.articulo', 'cliente'])]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al procesar la venta: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $venta = Venta::with(['detalles.articulo', 'cliente', 'usuario'])->find($id);
        if($venta) {
            return response()->json(['venta' => $venta]);
        }
        return response()->json(['success' => false, 'message' => 'Venta no encontrada'], 404);
    }

    public function destroy($id)
    {
        $venta = Venta::with('detalles')->find($id);
        if($venta) {
            try {
                DB::beginTransaction();
                
                foreach ($venta->detalles as $detalle) {
                    // Revertir stock
                    $articulo = Articulo::find($detalle->articulo_id);
                    $stockAnterior = $articulo->stock;
                    $stockNuevo = $stockAnterior + $detalle->cantidad;
                    $articulo->update(['stock' => $stockNuevo]);

                    // Revertir lotes - recrear entrada lógica
                    Lote::create([
                        'articulo_id' => $detalle->articulo_id,
                        'cantidad' => $detalle->cantidad,
                        'cantidad_disponible' => $detalle->cantidad,
                        'costo_unitario' => $articulo->precio_venta,
                        'fecha_entrada' => now(),
                        'compra_id' => null,
                        'activo' => true
                    ]);

                    MovimientoInventario::create([
                        'articulo_id' => $detalle->articulo_id,
                        'tipo' => 'entrada',
                        'cantidad' => $detalle->cantidad,
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockNuevo,
                        'motivo' => 'Reversión de venta #' . $venta->id,
                        'documento_id' => $venta->id,
                        'documento_type' => Venta::class,
                        'user_id' => auth()->id()
                    ]);
                }

                $venta->detalles()->delete();
                $venta->delete();
                
                DB::commit();
                return response()->json(['success' => true]);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }
        return response()->json(['success' => false, 'message' => 'Venta no encontrada'], 404);
    }
}

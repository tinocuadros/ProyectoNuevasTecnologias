<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Articulo;
use App\Models\Lote;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $query = Compra::with(['proveedor', 'usuario']);
        
        if ($request->numero_factura) {
            $query->where('numero_factura', 'like', '%' . $request->numero_factura . '%');
        }
        if ($request->proveedor_id) {
            $query->where('proveedor_id', $request->proveedor_id);
        }
        if ($request->fecha_inicio && $request->fecha_fin) {
            $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
        }
        
        $compras = $query->orderBy('fecha', 'desc')->paginate(10);
        return response()->json($compras);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero_factura' => 'nullable|unique:compras',
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha' => 'required|date',
            'observaciones' => 'nullable',
            'detalles' => 'required|array|min:1',
            'detalles.*.articulo_id' => 'required|exists:articulos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            $compra = Compra::create([
                'numero_factura' => $data['numero_factura'],
                'proveedor_id' => $data['proveedor_id'],
                'fecha' => $data['fecha'],
                'observaciones' => $data['observaciones'],
                'user_id' => auth()->id(),
                'total' => 0
            ]);

            $total = 0;
            foreach ($data['detalles'] as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'articulo_id' => $detalle['articulo_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $subtotal
                ]);
                $total += $subtotal;

                // Crear lote (PEPS)
                Lote::create([
                    'articulo_id' => $detalle['articulo_id'],
                    'cantidad' => $detalle['cantidad'],
                    'cantidad_disponible' => $detalle['cantidad'],
                    'costo_unitario' => $detalle['precio_unitario'],
                    'fecha_entrada' => $data['fecha'],
                    'compra_id' => $compra->id,
                    'activo' => true
                ]);

                // Actualizar stock del artículo
                $articulo = Articulo::find($detalle['articulo_id']);
                $stockAnterior = $articulo->stock;
                $stockNuevo = $stockAnterior + $detalle['cantidad'];
                $articulo->update(['stock' => $stockNuevo]);

                // Registro de movimiento
                MovimientoInventario::create([
                    'articulo_id' => $detalle['articulo_id'],
                    'tipo' => 'entrada',
                    'cantidad' => $detalle['cantidad'],
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $stockNuevo,
                    'motivo' => 'Compra #' . $compra->id,
                    'documento_id' => $compra->id,
                    'documento_type' => Compra::class,
                    'user_id' => auth()->id()
                ]);
            }

            $compra->update(['total' => $total]);
            DB::commit();

            return response()->json(['success' => true, 'compra' => $compra->load(['detalles.articulo', 'proveedor'])]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al procesar la compra: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $compra = Compra::with(['detalles.articulo', 'proveedor', 'usuario'])->find($id);
        if($compra) {
            return response()->json(['compra' => $compra]);
        }
        return response()->json(['success' => false, 'message' => 'Compra no encontrada'], 404);
    }

    public function destroy($id)
    {
        $compra = Compra::with('detalles')->find($id);
        if($compra) {
            try {
                DB::beginTransaction();
                
                foreach ($compra->detalles as $detalle) {
                    // Revertir stock
                    $articulo = Articulo::find($detalle->articulo_id);
                    $stockAnterior = $articulo->stock;
                    $stockNuevo = $stockAnterior - $detalle->cantidad;
                    
                    if ($stockNuevo < 0) {
                        throw new \Exception('Stock insuficiente para revertir la compra');
                    }
                    
                    $articulo->update(['stock' => $stockNuevo]);

                    // Eliminar lote asociado a esta compra para este artículo
                    Lote::where('compra_id', $compra->id)
                        ->where('articulo_id', $detalle->articulo_id)
                        ->delete();

                    // Registro de movimiento de reversión
                    MovimientoInventario::create([
                        'articulo_id' => $detalle->articulo_id,
                        'tipo' => 'salida',
                        'cantidad' => $detalle->cantidad,
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockNuevo,
                        'motivo' => 'Reversión de compra #' . $compra->id,
                        'documento_id' => $compra->id,
                        'documento_type' => Compra::class,
                        'user_id' => auth()->id()
                    ]);
                }

                $compra->detalles()->delete();
                $compra->delete();
                
                DB::commit();
                return response()->json(['success' => true]);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }
        return response()->json(['success' => false, 'message' => 'Compra no encontrada'], 404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('cliente', 'usuario')->orderByDesc('id_venta')->get();
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes  = Cliente::orderBy('nombre')->get();
        $productos = Producto::where('stock', '>', 0)->orderBy('nombre')->get();
        $usuarios  = Usuario::orderBy('nombre')->get();
        return view('ventas.create', compact('clientes', 'productos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente'   => 'required|integer',
            'id_usuario'   => 'required|integer',
            'metodo_pago'  => 'required|in:Efectivo,Tarjeta,Transferencia',
            'productos'    => 'required|array|min:1',
            'productos.*.id_producto'    => 'required|integer',
            'productos.*.cantidad'       => 'required|integer|min:1',
            'productos.*.precio_unitario'=> 'required|numeric|min:0',
        ]);

        $total = collect($request->productos)->sum(function ($item) {
            return $item['cantidad'] * $item['precio_unitario'];
        });

        $venta = Venta::create([
            'id_cliente'  => $request->id_cliente,
            'id_usuario'  => $request->id_usuario,
            'metodo_pago' => $request->metodo_pago,
            'total'       => $total,
        ]);

        foreach ($request->productos as $item) {
            DetalleVenta::create([
                'id_venta'        => $venta->id_venta,
                'id_producto'     => $item['id_producto'],
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
            ]);

            // Descontar stock
            $producto = Producto::find($item['id_producto']);
            if ($producto) {
                $producto->stock -= $item['cantidad'];
                $producto->save();
            }
        }

        return redirect()->route('ventas.show', $venta->id_venta)
                         ->with('success', 'Venta registrada correctamente.');
    }

    public function show($id)
    {
        $venta = Venta::with('cliente', 'usuario', 'detalles.producto')->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    public function generarPdf($id)
    {
        $venta = Venta::with('cliente', 'usuario', 'detalles.producto')->findOrFail($id);
        $pdf = Pdf::loadView('ventas.pdf', compact('venta'));
        return $pdf->download("factura-{$id}.pdf");
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Articulo;

class ArticuloController extends Controller
{
    public function index(Request $request)
    {
        $query = Articulo::query();
        
        if ($request->codigo) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }
        if ($request->nombre) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->ubicacion) {
            $query->where('ubicacion', 'like', '%' . $request->ubicacion . '%');
        }
        
        if ($request->stock === 'bajo') {
            $query->whereColumn('stock', '<=', 'stock_minimo');
        } elseif ($request->stock === 'agotado') {
            $query->where('stock', 0);
        } elseif ($request->stock === 'normal') {
            $query->whereColumn('stock', '>', 'stock_minimo');
        }
        
        $articulos = $query->orderBy('nombre')->paginate(10);
        return response()->json($articulos);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'nullable|unique:articulos',
            'nombre' => 'required|unique:articulos',
            'descripcion' => 'nullable',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'ubicacion' => 'nullable'
        ]);

        $articulo = Articulo::create($data);
        return response()->json(['success' => true, 'articulo' => $articulo]);
    }

    public function show($id)
    {
        $articulo = Articulo::find($id);
        if($articulo) {
            return response()->json(['articulo' => $articulo]);
        }
        return response()->json(['success' => false, 'message' => 'Artículo no encontrado'], 404);
    }

    public function update(Request $request, $id)
    {
        $articulo = Articulo::find($id);
        if($articulo) {
            $data = $request->validate([
                'codigo' => 'nullable|unique:articulos,codigo,' . $id,
                'nombre' => 'required|unique:articulos,nombre,' . $id,
                'descripcion' => 'nullable',
                'precio_venta' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'stock_minimo' => 'required|integer|min:0',
                'ubicacion' => 'nullable'
            ]);
            $articulo->update($data);
            return response()->json(['success' => true, 'articulo' => $articulo]);
        }
        return response()->json(['success' => false, 'message' => 'Artículo no encontrado'], 404);
    }

    public function destroy($id)
    {
        $articulo = Articulo::find($id);
        if($articulo) {
            $articulo->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Artículo no encontrado'], 404);
    }
}

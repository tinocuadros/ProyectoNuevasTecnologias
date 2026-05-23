<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::query();
        
        if ($request->codigo) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }
        if ($request->nombre) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->ruc) {
            $query->where('ruc', 'like', '%' . $request->ruc . '%');
        }
        if ($request->activo !== null && $request->activo !== '') {
            $query->where('activo', $request->activo);
        }
        
        $proveedores = $query->orderBy('nombre')->paginate(10);
        return response()->json($proveedores);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'nullable|unique:proveedores',
            'nombre' => 'required|unique:proveedores',
            'ruc' => 'nullable|unique:proveedores',
            'telefono' => 'nullable',
            'email' => 'nullable|email',
            'direccion' => 'nullable',
            'contacto_nombre' => 'nullable',
            'contacto_telefono' => 'nullable'
        ]);

        $proveedor = Proveedor::create($data);
        return response()->json(['success' => true, 'proveedor' => $proveedor]);
    }

    public function show($id)
    {
        $proveedor = Proveedor::find($id);
        if($proveedor) {
            return response()->json(['proveedor' => $proveedor]);
        }
        return response()->json(['success' => false, 'message' => 'Proveedor no encontrado'], 404);
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::find($id);
        if($proveedor) {
            $data = $request->validate([
                'codigo' => 'nullable|unique:proveedores,codigo,'.$id,
                'nombre' => 'required|unique:proveedores,nombre,'.$id,
                'ruc' => 'nullable|unique:proveedores,ruc,'.$id,
                'telefono' => 'nullable',
                'email' => 'nullable|email',
                'direccion' => 'nullable',
                'contacto_nombre' => 'nullable',
                'contacto_telefono' => 'nullable'
            ]);
            $proveedor->update($data);
            return response()->json(['success' => true, 'proveedor' => $proveedor]);
        }
        return response()->json(['success' => false, 'message' => 'Proveedor no encontrado'], 404);
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::find($id);
        if($proveedor) {
            $proveedor->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Proveedor no encontrado'], 404);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();
        
        if ($request->codigo) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }
        if ($request->nombre) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->cedula) {
            $query->where('cedula', 'like', '%' . $request->cedula . '%');
        }
        if ($request->activo !== null && $request->activo !== '') {
            $query->where('activo', $request->activo);
        }
        
        $clientes = $query->orderBy('nombre')->paginate(10);
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'nullable|unique:clientes',
            'nombre' => 'required|unique:clientes',
            'cedula' => 'nullable|unique:clientes',
            'telefono' => 'nullable',
            'email' => 'nullable|email',
            'direccion' => 'nullable'
        ]);

        $cliente = Cliente::create($data);
        return response()->json(['success' => true, 'cliente' => $cliente]);
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);
        if($cliente) {
            return response()->json(['cliente' => $cliente]);
        }
        return response()->json(['success' => false, 'message' => 'Cliente no encontrado'], 404);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        if($cliente) {
            $data = $request->validate([
                'codigo' => 'nullable|unique:clientes,codigo,'.$id,
                'nombre' => 'required|unique:clientes,nombre,'.$id,
                'cedula' => 'nullable|unique:clientes,cedula,'.$id,
                'telefono' => 'nullable',
                'email' => 'nullable|email',
                'direccion' => 'nullable'
            ]);
            $cliente->update($data);
            return response()->json(['success' => true, 'cliente' => $cliente]);
        }
        return response()->json(['success' => false, 'message' => 'Cliente no encontrado'], 404);
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if($cliente) {
            $cliente->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Cliente no encontrado'], 404);
    }
}

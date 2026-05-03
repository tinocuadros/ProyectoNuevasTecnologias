<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permiso;

class PermisoController extends Controller
{
    public function index()
    {
        return response()->json(['permisos' => Permiso::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|unique:permisos',
            'slug' => 'required|unique:permisos',
            'descripcion' => 'nullable',
            'modulo' => 'nullable'
        ]);

        $permiso = Permiso::create($data);
        
        return response()->json(['success' => true, 'permiso' => $permiso]);
    }

    public function show($id)
    {
        $permiso = Permiso::find($id);
        if($permiso) {
            return response()->json(['permiso' => $permiso]);
        }
        return response()->json(['success' => false, 'message' => 'Permiso no encontrado'], 404);
    }

    public function update(Request $request, $id)
    {
        $permiso = Permiso::find($id);
        if($permiso) {
            $data = $request->validate([
                'nombre' => 'required|unique:permisos,nombre,' . $id,
                'slug' => 'required|unique:permisos,slug,' . $id,
                'descripcion' => 'nullable',
                'modulo' => 'nullable'
            ]);
            $permiso->update($data);
            return response()->json(['success' => true, 'permiso' => $permiso]);
        }
        return response()->json(['success' => false, 'message' => 'Permiso no encontrado'], 404);
    }

    public function destroy($id)
    {
        $permiso = Permiso::find($id);
        if($permiso) {
            $permiso->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Permiso no encontrado'], 404);
    }
}

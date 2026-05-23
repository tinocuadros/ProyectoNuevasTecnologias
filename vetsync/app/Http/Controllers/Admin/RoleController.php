<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(['roles' => Role::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|unique:roles',
            'slug' => 'required|unique:roles',
            'descripcion' => 'nullable'
        ]);

        $role = Role::create($data);
        
        return response()->json(['success' => true, 'rol' => $role]);
    }

    public function show($id)
    {
        $role = Role::with('permisos')->find($id);
        if($role) {
            return response()->json(['rol' => $role]);
        }
        return response()->json(['success' => false, 'message' => 'Rol no encontrado'], 404);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if($role) {
            $data = $request->validate([
                'nombre' => 'required|unique:roles,nombre,' . $id,
                'slug' => 'required|unique:roles,slug,' . $id,
                'descripcion' => 'nullable'
            ]);
            $role->update($data);
            return response()->json(['success' => true, 'rol' => $role]);
        }
        return response()->json(['success' => false, 'message' => 'Rol no encontrado'], 404);
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if($role) {
            $role->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Rol no encontrado'], 404);
    }

    public function assignPermisos(Request $request, $id)
    {
        $role = Role::find($id);
        if($role) {
            $permisos = $request->validate([
                'permisos' => 'required|array'
            ]);
            $role->permisos()->sync($permisos['permisos']);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Rol no encontrado'], 404);
    }
}

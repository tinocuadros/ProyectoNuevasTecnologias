<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:usuarios,username',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:Administrador,Vendedor,Auxiliar Compras,Auxiliar Inventario,Jefe Ventas',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'estado' => true,
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario registrado correctamente.');
    }

    public function show(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:usuarios,username,' . $id . ',id_usuario',
            'password' => 'nullable|string|min:6|confirmed',
            'rol' => 'required|in:Administrador,Vendedor,Auxiliar Compras,Auxiliar Inventario,Jefe Ventas',
        ]);

        $usuario = Usuario::findOrFail($id);
        $data = [
            'nombre' => $request->nombre,
            'username' => $request->username,
            'rol' => $request->rol,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario eliminado correctamente.');
    }
}
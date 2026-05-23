<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->cedula) {
            $query->where('cedula', 'like', "%{$request->cedula}%");
        }
        
        if ($request->nombre) {
            $query->where(function($q) use ($request) {
                $q->where('primer_nombre', 'like', "%{$request->nombre}%")
                  ->orWhere('segundo_nombre', 'like', "%{$request->nombre}%")
                  ->orWhere('primer_apellido', 'like', "%{$request->nombre}%")
                  ->orWhere('segundo_apellido', 'like', "%{$request->nombre}%");
            });
        }
        
        if ($request->username) {
            $query->where('username', 'like', "%{$request->username}%");
        }
        
        if ($request->email) {
            $query->where('email', 'like', "%{$request->email}%");
        }
        
        if ($request->activo !== null && $request->activo !== '') {
            $query->where('activo', $request->activo);
        }
        
        if ($request->rol_id) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('roles.id', $request->rol_id);
            });
        }

        $usuarios = $query->orderBy('id', 'asc')->paginate(20);
        
        $activeSessions = DB::table('sessions')
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();
        
        return response()->json([
            'usuarios' => $usuarios, 
            'current_user_id' => Auth::id(),
            'active_user_ids' => $activeSessions
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'cedula' => 'nullable|unique:users',
                'primer_nombre' => 'required',
                'segundo_nombre' => 'nullable',
                'primer_apellido' => 'required',
                'segundo_apellido' => 'nullable',
                'username' => 'required|unique:users'
            ]);

            $data['name'] = $data['primer_nombre'] . ' ' . $data['primer_apellido'];
            $data['activo'] = true;
            
            $user = User::create($data);
            
            return response()->json(['success' => true, 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('roles')->find($id);
            if($user) {
                $activeSessions = DB::table('sessions')
                    ->whereNotNull('user_id')
                    ->pluck('user_id')
                    ->toArray();
                return response()->json([
                    'user' => $user,
                    'is_online' => in_array($user->id, $activeSessions)
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if($user) {
                $data = $request->validate([
                    'email' => 'required|email|unique:users,email,' . $id,
                    'cedula' => 'nullable|unique:users,cedula,' . $id,
                    'primer_nombre' => 'required',
                    'segundo_nombre' => 'nullable',
                    'primer_apellido' => 'required',
                    'segundo_apellido' => 'nullable',
                    'username' => 'required|unique:users,username,' . $id
                ]);
                $user->update($data);
                return response()->json(['success' => true, 'user' => $user]);
            }
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if($user) {
            $user->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
    }

    public function assignRoles(Request $request, $id)
    {
        $user = User::find($id);
        if($user) {
            $roles = $request->validate([
                'roles' => 'required|array'
            ]);
            $user->roles()->sync($roles['roles']);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
    }

    public function toggleActive(Request $request, $id)
    {
        $user = User::find($id);
        if($user) {
            $user->update(['activo' => !$user->activo]);
            return response()->json(['success' => true, 'activo' => $user->activo]);
        }
        return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
    }
}

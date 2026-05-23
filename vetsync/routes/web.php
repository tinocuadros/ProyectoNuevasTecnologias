<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermisoController;

/*
|--------------------------------------------------------------------------
| RUTAS PARA INVITADOS (GUEST)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $login = $request->input('email');

        $user = User::where('email', $login)
            ->orWhere('username', $login)
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado']);
        }

        if (!$user->activo) {
            Auth::logout();
            return back()->withErrors(['email' => 'Usuario inactivo. Contacte al administrador del sistema.']);
        }

        if (Auth::attempt(['email' => $user->email, 'password' => $request->input('password')])) {
            $request->session()->regenerate();

            $user->update([
                'session_id' => $request->session()->getId(),
                'ultimo_login' => now()
            ]);

            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    });

    // Registro
    Route::get('/register', function () {
        return view('auth.register');
    });

    Route::post('/register', function (Request $request) {
        $data = $request->validate([
            'primer_nombre' => 'required',
            'primer_apellido' => 'required',
            'segundo_nombre' => 'nullable',
            'segundo_apellido' => 'nullable',
            'cedula' => 'nullable|unique:users,cedula',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // Generar username: inicial + apellido (lowercase)
        $username = strtolower($data['primer_nombre'][0] . $data['primer_apellido']);

        // Verificar si existe, si existe agregar número
        $baseUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'primer_nombre' => $data['primer_nombre'],
            'segundo_nombre' => $data['segundo_nombre'] ?? null,
            'primer_apellido' => $data['primer_apellido'],
            'segundo_apellido' => $data['segundo_apellido'] ?? null,
            'cedula' => $data['cedula'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'username' => $username
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect('/');
    });

    // Recuperación de Contraseña
    Route::get('/recuperar_contrasenia', function () {
        return view('auth.recuperar_contrasenia');
    })->name('password.request');

    Route::post('/recuperar_contrasenia', function (Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.exists' => 'Este correo no está registrado en nuestro sistema.'
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $url = url("/resetear-password/{$token}?email={$request->email}");

        Mail::to($request->email)->send(new \App\Mail\RecuperarPasswordMail($url));

        return back()->with('message', '¡Listo! Revisa tu correo para cambiar tu contraseña.');
    });

    // Mostrar formulario de nueva contraseña
    Route::get('/resetear-password/{token}', function (Request $request, $token) {
        return view('auth.reset_password', ['token' => $token, 'email' => $request->email]);
    })->name('password.reset');

    // Procesar la nueva contraseña
    Route::post('/resetear-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Este enlace de recuperación es inválido o ha expirado.']);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('message', 'Contraseña actualizada con éxito. Ya puedes iniciar sesión.');
    });
});

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (AUTH & VERIFIED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Rutas accesibles para todos los usuarios autenticados
    Route::get('/', function () {
        return view('Home');
    });

    Route::get('/perfil', [ProfileController::class, 'index']);

    Route::patch('/perfil', function (Request $request) {
        $user = $request->user();
        $data = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cedula' => 'nullable|unique:users,cedula,' . $user->id,
            'primer_nombre' => 'required',
            'segundo_nombre' => 'nullable',
            'primer_apellido' => 'required',
            'segundo_apellido' => 'nullable',
        ]);

        $user->update($data);

        return response()->json(['success' => true, 'message' => 'Perfil actualizado correctamente']);
    });

    Route::post('/perfil/contrasena', function (Request $request) {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'La contraseña actual es incorrecta'], 422);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['success' => true, 'message' => 'Contraseña actualizada correctamente']);
    });

    // Ruta de perfiles (accesible para gestionar roles y permisos)
    Route::get('/perfiles', function(){
        return view('administracion.perfiles_usuario');
    });

    // Rutas de administración protegidas por permiso
    Route::middleware(['permission:acceso-admin'])->group(function () {

        Route::get('/admin/usuarios', function(){
            return view('administracion.usuarios');
        });

        Route::get('/permisos', function(){
            return view('administracion.permisos');
        });

        // API Usuarios
        Route::get('/api/admin/usuarios', [UserController::class, 'index']);
        Route::post('/api/admin/usuarios', [UserController::class, 'store']);
        Route::get('/api/admin/usuario/{id}', [UserController::class, 'show']);
        Route::put('/api/admin/usuarios/{id}', [UserController::class, 'update']);
        Route::delete('/api/admin/usuarios/{id}', [UserController::class, 'destroy']);
        Route::post('/api/admin/usuario/{id}/roles', [UserController::class, 'assignRoles']);
        Route::post('/api/admin/usuario/{id}/toggle-activo', [UserController::class, 'toggleActive']);

        // API Roles
        Route::get('/api/admin/roles', [RoleController::class, 'index']);
        Route::post('/api/admin/roles', [RoleController::class, 'store']);
        Route::get('/api/admin/roles/{id}', [RoleController::class, 'show']);
        Route::put('/api/admin/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/api/admin/roles/{id}', [RoleController::class, 'destroy']);
        Route::post('/api/admin/roles/{id}/permisos', [RoleController::class, 'assignPermisos']);

        // API Permisos
        Route::get('/api/admin/permisos', [PermisoController::class, 'index']);
        Route::post('/api/admin/permisos', [PermisoController::class, 'store']);
        Route::get('/api/admin/permiso/{id}', [PermisoController::class, 'show']);
        Route::put('/api/admin/permisos/{id}', [PermisoController::class, 'update']);
        Route::delete('/api/admin/permisos/{id}', [PermisoController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| RUTAS DE VERIFICACIÓN DE EMAIL
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '¡Enlace de verificación enviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| SALIDA Y PRUEBAS
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {
    $user = $request->user();
    if ($user) {
        $user->update([
            'ultimo_login' => now(),
            'session_id' => null
        ]);
    }
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
});

Route::get('/test-mail', function () {
    Mail::raw('Funciona Gmail 🚀', function ($message) {
        $message->to('cagaitan340@gmail.com')->subject('Prueba Laravel');
    });
    return 'Correo enviado';
});

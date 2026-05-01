<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;

// Redirige según si está logueado o no
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Login
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('productos', ProductoController::class);
    Route::resource('usuarios',  UsuarioController::class);

    Route::get('/ventas',          [VentaController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/crear',    [VentaController::class, 'create'])->name('ventas.create');
    Route::post('/ventas',         [VentaController::class, 'store'])->name('ventas.store');
    Route::get('/ventas/{id}',     [VentaController::class, 'show'])->name('ventas.show');
    Route::get('/ventas/{id}/pdf', [VentaController::class, 'generarPdf'])->name('ventas.pdf');

});
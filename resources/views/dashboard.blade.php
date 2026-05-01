@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- Bienvenida --}}
    <div class="row mb-4">
        <div class="col">
            <h2>Bienvenido, {{ auth()->user()->nombre }} 👋</h2>
            <p class="text-muted">Panel principal del sistema de Gestión de Inventario</p>
        </div>
        <div class="col-auto">
            <span class="badge bg-secondary fs-6">{{ auth()->user()->rol }}</span>
        </div>
    </div>

    {{-- Tarjetas de módulos --}}
    <div class="row g-4">

        {{-- Productos --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white bg-primary h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title fw-bold">Productos</h6>
                        <p class="mb-0">Gestionar inventario</p>
                    </div>
                    <i class="bi bi-box-seam fs-1"></i>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('productos.index') }}" class="text-white text-decoration-none fw-semibold">
                        Ir a Productos →
                    </a>
                </div>
            </div>
        </div>

        {{-- Ventas --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white bg-success h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title fw-bold">Ventas</h6>
                        <p class="mb-0">Registrar y consultar ventas</p>
                    </div>
                    <i class="bi bi-cart-check fs-1"></i>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('ventas.index') }}" class="text-white text-decoration-none fw-semibold">
                        Ir a Ventas →
                    </a>
                </div>
            </div>
        </div>

        {{-- Usuarios --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white bg-warning h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title fw-bold">Usuarios</h6>
                        <p class="mb-0">Gestionar usuarios del sistema</p>
                    </div>
                    <i class="bi bi-people fs-1"></i>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('usuarios.index') }}" class="text-white text-decoration-none fw-semibold">
                        Ir a Usuarios →
                    </a>
                </div>
            </div>
        </div>

        {{-- Administración --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white bg-info h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title fw-bold">Administración</h6>
                        <p class="mb-0">Perfiles y configuración</p>
                    </div>
                    <i class="bi bi-gear fs-1"></i>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ url('/perfiles') }}" class="text-white text-decoration-none fw-semibold">
                        Ir a Administración →
                    </a>
                </div>
            </div>
        </div>

        {{-- Nueva Venta rápida --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white bg-danger h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title fw-bold">Nueva Venta</h6>
                        <p class="mb-0">Registrar venta rápida</p>
                    </div>
                    <i class="bi bi-plus-circle fs-1"></i>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('ventas.create') }}" class="text-white text-decoration-none fw-semibold">
                        Crear Venta →
                    </a>
                </div>
            </div>
        </div>

        {{-- Cerrar sesión --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white bg-dark h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title fw-bold">Sesión</h6>
                        <p class="mb-0">{{ auth()->user()->username }}</p>
                    </div>
                    <i class="bi bi-box-arrow-right fs-1"></i>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-white text-decoration-none fw-semibold p-0">
                            Cerrar sesión →
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
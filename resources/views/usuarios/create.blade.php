@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="bi bi-person-plus"></i> Registrar Nuevo Usuario</h4>
    </div>
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Nombre Completo *</label>
                <input type="text" name="nombre" class="form-control"
                       value="{{ old('nombre') }}" placeholder="Ej: Juan Pérez" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre de Usuario *</label>
                    <input type="text" name="username" class="form-control"
                           value="{{ old('username') }}" placeholder="Ej: jperez" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Rol *</label>
                    <select name="rol" class="form-select" required>
                        <option value="">Seleccione un rol...</option>
                        <option value="Administrador" {{ old('rol') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Vendedor" {{ old('rol') == 'Vendedor' ? 'selected' : '' }}>Vendedor</option>
                        <option value="Auxiliar Compras" {{ old('rol') == 'Auxiliar Compras' ? 'selected' : '' }}>Auxiliar Compras</option>
                        <option value="Auxiliar Inventario" {{ old('rol') == 'Auxiliar Inventario' ? 'selected' : '' }}>Auxiliar Inventario</option>
                        <option value="Jefe Ventas" {{ old('rol') == 'Jefe Ventas' ? 'selected' : '' }}>Jefe Ventas</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Contraseña *</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="********" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Confirmar Contraseña *</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="********" required>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Usuario
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection
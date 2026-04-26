@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0"><i class="bi bi-person-gear"></i> Editar Usuario</h4>
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

        <form action="{{ route('usuarios.update', $usuario->id_usuario) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Nombre Completo *</label>
                <input type="text" name="nombre" class="form-control"
                       value="{{ old('nombre', $usuario->nombre) }}" placeholder="Ej: Juan Pérez" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre de Usuario *</label>
                    <input type="text" name="username" class="form-control"
                           value="{{ old('username', $usuario->username) }}" placeholder="Ej: jperez" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Rol *</label>
                    <select name="rol" class="form-select" required>
                        <option value="">Seleccione un rol...</option>
                        <option value="Administrador" {{ old('rol', $usuario->rol) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Vendedor" {{ old('rol', $usuario->rol) == 'Vendedor' ? 'selected' : '' }}>Vendedor</option>
                        <option value="Auxiliar Compras" {{ old('rol', $usuario->rol) == 'Auxiliar Compras' ? 'selected' : '' }}>Auxiliar Compras</option>
                        <option value="Auxiliar Inventario" {{ old('rol', $usuario->rol) == 'Auxiliar Inventario' ? 'selected' : '' }}>Auxiliar Inventario</option>
                        <option value="Jefe Ventas" {{ old('rol', $usuario->rol) == 'Jefe Ventas' ? 'selected' : '' }}>Jefe Ventas</option>
                    </select>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Solo complete la contraseña si desea cambiarla.
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nueva Contraseña</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="********">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="********">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Actualizar Usuario
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
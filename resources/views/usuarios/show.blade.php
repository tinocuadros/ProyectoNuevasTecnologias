@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="bi bi-person"></i> Detalles del Usuario</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nombre Completo</label>
                <p class="form-control-plaintext">{{ $usuario->nombre }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nombre de Usuario</label>
                <p class="form-control-plaintext">{{ $usuario->username }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Rol</label>
                <p class="form-control-plaintext"><span class="badge bg-info">{{ $usuario->rol }}</span></p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Estado</label>
                <p class="form-control-plaintext">
                    @if($usuario->estado)
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-secondary">Inactivo</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Creación</label>
                <p class="form-control-plaintext">{{ $usuario->fecha_creacion }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Última Actualización</label>
                <p class="form-control-plaintext">{{ $usuario->fecha_actualizacion ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

</div>
</div>
@endsection
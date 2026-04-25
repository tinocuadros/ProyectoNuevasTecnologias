@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card shadow-sm">
    <div class="card-header bg-warning">
        <h4 class="mb-0"><i class="bi bi-pencil"></i> Editar Producto: {{ $producto->nombre }}</h4>
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

        <form action="{{ route('productos.update', $producto->id_producto) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Nombre del Producto *</label>
                <input type="text" name="nombre" class="form-control"
                       value="{{ old('nombre', $producto->nombre) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Precio (COP) *</label>
                    <input type="number" name="precio" class="form-control"
                           value="{{ old('precio', $producto->precio) }}" step="0.01" min="0" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Stock *</label>
                    <input type="number" name="stock" class="form-control"
                           value="{{ old('stock', $producto->stock) }}" min="0" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Stock Mínimo *</label>
                    <input type="number" name="stock_minimo" class="form-control"
                           value="{{ old('stock_minimo', $producto->stock_minimo) }}" min="0" required>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Actualizar Producto
                </button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection
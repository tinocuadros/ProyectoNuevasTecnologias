@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0"><i class="bi bi-eye"></i> Detalle del Producto</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th width="200">ID</th><td>{{ $producto->id_producto }}</td></tr>
            <tr><th>Nombre</th><td>{{ $producto->nombre }}</td></tr>
            <tr><th>Descripción</th><td>{{ $producto->descripcion ?? '—' }}</td></tr>
            <tr><th>Precio</th><td>$ {{ number_format($producto->precio, 2) }}</td></tr>
            <tr><th>Stock</th><td>{{ $producto->stock }}</td></tr>
            <tr><th>Stock Mínimo</th><td>{{ $producto->stock_minimo }}</td></tr>
            <tr>
                <th>Estado</th>
                <td>
                    @if($producto->stock <= $producto->stock_minimo)
                        <span class="badge bg-danger fs-6">
                            <i class="bi bi-exclamation-triangle"></i> Stock Bajo
                        </span>
                    @else
                        <span class="badge bg-success fs-6">Disponible</span>
                    @endif
                </td>
            </tr>
        </table>

        <div class="d-flex gap-2">
            <a href="{{ route('productos.edit', $producto->id_producto) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

</div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-box-seam"></i> Gestión de Productos</h2>
    <a href="{{ route('productos.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nuevo Producto
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        @if($productos->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox" style="font-size:3rem;"></i>
                <p class="mt-2">No hay productos registrados.</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Stock Mínimo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr>
                        <td>{{ $producto->id_producto }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>$ {{ number_format($producto->precio, 2) }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>{{ $producto->stock_minimo }}</td>
                        <td>
                            @if($producto->stock <= $producto->stock_minimo)
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle"></i> Stock Bajo
                                </span>
                            @else
                                <span class="badge bg-success">Disponible</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('productos.show', $producto->id_producto) }}" class="btn btn-sm btn-info text-white" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('productos.edit', $producto->id_producto) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('productos.destroy', $producto->id_producto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar {{ $producto->nombre }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Listado de Ventas</h2>
        <a href="{{ route('ventas.create') }}" class="btn btn-primary">+ Nueva Venta</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Método Pago</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventas as $venta)
            <tr>
                <td>{{ $venta->id_venta }}</td>
                <td>{{ $venta->cliente->nombre }}</td>
                <td>{{ $venta->usuario->nombre }}</td>
                <td>{{ $venta->fecha_venta }}</td>
                <td>${{ number_format($venta->total, 2) }}</td>
                <td>{{ $venta->metodo_pago }}</td>
                <td>
                    <a href="{{ route('ventas.show', $venta->id_venta) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('ventas.pdf', $venta->id_venta) }}" class="btn btn-sm btn-danger">PDF</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">No hay ventas registradas</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
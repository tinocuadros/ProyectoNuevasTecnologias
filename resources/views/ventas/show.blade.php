<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Venta #{{ $venta->id_venta }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Venta #{{ $venta->id_venta }}</h2>
        <div>
            <a href="{{ route('ventas.pdf', $venta->id_venta) }}" class="btn btn-danger">Descargar PDF</a>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <h5>Datos del Cliente</h5>
            <p><strong>Nombre:</strong> {{ $venta->cliente->nombre }}</p>
            <p><strong>Teléfono:</strong> {{ $venta->cliente->telefono ?? 'N/A' }}</p>
            <p><strong>Correo:</strong> {{ $venta->cliente->correo ?? 'N/A' }}</p>
            <p><strong>Dirección:</strong> {{ $venta->cliente->direccion ?? 'N/A' }}</p>
        </div>
        <div class="col-md-6">
            <h5>Datos de la Venta</h5>
            <p><strong>Vendedor:</strong> {{ $venta->usuario->nombre }}</p>
            <p><strong>Fecha:</strong> {{ $venta->fecha_venta }}</p>
            <p><strong>Método de Pago:</strong> {{ $venta->metodo_pago }}</p>
            <p><strong>Total:</strong> ${{ number_format($venta->total, 2) }}</p>
        </div>
    </div>

    <h5>Productos</h5>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Producto</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->nombre }}</td>
                <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>${{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td><strong>${{ number_format($venta->total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
</body>
</html>
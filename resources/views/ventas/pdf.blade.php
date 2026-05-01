<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $venta->id_venta }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 22px; margin: 0; }
        .header p { margin: 2px 0; }
        .info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info div { width: 48%; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #333; color: white; padding: 8px; text-align: left; }
        td { padding: 7px 8px; border-bottom: 1px solid #ddd; }
        .total-row td { font-weight: bold; font-size: 14px; background: #f5f5f5; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURA DE VENTA</h1>
        <p>Gestión Inventario</p>
        <p>Factura N°: {{ str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT) }}</p>
        <p>Fecha: {{ $venta->fecha_venta }}</p>
    </div>

    <div class="info">
        <div>
            <strong>CLIENTE</strong><br>
            Nombre: {{ $venta->cliente->nombre }}<br>
            Teléfono: {{ $venta->cliente->telefono ?? 'N/A' }}<br>
            Correo: {{ $venta->cliente->correo ?? 'N/A' }}<br>
            Dirección: {{ $venta->cliente->direccion ?? 'N/A' }}
        </div>
        <div>
            <strong>VENTA</strong><br>
            Vendedor: {{ $venta->usuario->nombre }}<br>
            Método de Pago: {{ $venta->metodo_pago }}<br>
            Fecha: {{ $venta->fecha_venta }}
        </div>
    </div>

    <table>
        <thead>
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
            <tr class="total-row">
                <td colspan="3" style="text-align:right">TOTAL:</td>
                <td>${{ number_format($venta->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Gracias por su compra — Generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
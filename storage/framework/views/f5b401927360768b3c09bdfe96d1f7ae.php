<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura <?php echo e($venta->numero_factura); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; font-size: 12px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .company-info { width: 50%; }
        .invoice-info { width: 40%; text-align: right; }
        .invoice-title { font-size: 28px; color: #2c3e50; margin-bottom: 10px; }
        .client-section { margin-bottom: 30px; }
        .section-title { background: #f8f9fa; padding: 8px; font-weight: bold; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #2c3e50; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .total-section { margin-top: 30px; text-align: right; }
        .total-row { display: flex; justify-content: flex-end; margin: 5px 0; }
        .total-label { width: 150px; font-weight: bold; }
        .total-value { width: 100px; }
        .grand-total { font-size: 16px; color: #2c3e50; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h2 style="margin: 0 0 10px 0;">INVENTARIO S.A.</h2>
            <p style="margin: 2px 0;">Dirección: Calle Principal #123</p>
            <p style="margin: 2px 0;">Teléfono: (123) 456-7890</p>
            <p style="margin: 2px 0;">Email: info@inventario.com</p>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">FACTURA</div>
            <p style="margin: 2px 0;"><strong>Número:</strong> <?php echo e($venta->numero_factura); ?></p>
            <p style="margin: 2px 0;"><strong>Fecha:</strong> <?php echo e(date('d/m/Y', strtotime($venta->fecha_venta))); ?></p>
            <p style="margin: 2px 0;"><strong>Estado:</strong> <?php echo e(ucfirst($venta->estado)); ?></p>
        </div>
    </div>

    <div class="client-section">
        <div class="section-title">DATOS DEL CLIENTE</div>
        <div style="display: flex;">
            <div style="width: 50%;">
                <p style="margin: 2px 0;"><strong>Nombre:</strong> <?php echo e($venta->cliente->nombre ?? 'N/A'); ?></p>
                <p style="margin: 2px 0;"><strong>Cédula:</strong> <?php echo e($venta->cliente->cedula ?? 'N/A'); ?></p>
            </div>
            <div style="width: 50%;">
                <p style="margin: 2px 0;"><strong>Teléfono:</strong> <?php echo e($venta->cliente->telefono ?? 'N/A'); ?></p>
                <p style="margin: 2px 0;"><strong>Email:</strong> <?php echo e($venta->cliente->email ?? 'N/A'); ?></p>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $venta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($detalle->articulo->nombre ?? 'N/A'); ?></td>
                <td><?php echo e($detalle->cantidad); ?></td>
                <td>$<?php echo e(number_format($detalle->precio_unitario, 2)); ?></td>
                <td>$<?php echo e(number_format($detalle->subtotal, 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <div class="total-label">Subtotal:</div>
            <div class="total-value">$<?php echo e(number_format($venta->total, 2)); ?></div>
        </div>
        <div class="total-row">
            <div class="total-label">IVA (0%):</div>
            <div class="total-value">$0.00</div>
        </div>
        <div class="total-row grand-total">
            <div class="total-label">TOTAL:</div>
            <div class="total-value">$<?php echo e(number_format($venta->total, 2)); ?></div>
        </div>
    </div>

    <div class="footer">
        <p>¡Gracias por su compra!</p>
        <p>Este documento es una representación impresa de la factura electrónica</p>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\inventario\resources\views/ventas/factura_pdf.blade.php ENDPATH**/ ?>
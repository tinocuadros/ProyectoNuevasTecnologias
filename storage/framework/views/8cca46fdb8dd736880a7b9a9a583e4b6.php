<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        .header-bar {
            background: #2c3e50;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-bar h3 {
            margin: 0;
            font-size: 16px;
        }
        .btn-back {
            background: #34495e;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        .pdf-container {
            height: calc(100vh - 50px);
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <div class="header-bar">
        <h3>Factura #<?php echo e($id); ?></h3>
        <a href="javascript:void(0)" onclick="window.close()" class="btn-back">Cerrar Ventana</a>
    </div>
    <div class="pdf-container">
        <iframe src="<?php echo e(url('/ventas/factura/' . $id)); ?>"></iframe>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\inventario\resources\views/ventas/ver_factura.blade.php ENDPATH**/ ?>
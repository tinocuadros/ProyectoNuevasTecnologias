<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifica tu cuenta - VetSync</title>
    <style>
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 40px 10px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 4px; /* Un poco más cuadrado como tu dropdown */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        .card-body {
            padding: 40px 30px;
            text-align: center;
        }
        .logo {
            max-height: 90px;
            margin-bottom: 25px;
        }
        h3 {
            color: #0a2f6b; /* Usando tu azul custom para el título */
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        p {
            color: #4a5568;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        /* EL BOTÓN CON TU COLOR .BG-CUSTOM */
        .btn-custom {
            background-color: #0a2f6b; 
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 2px; /* Más cuadrado siguiendo tu estilo */
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        /* Efecto hover simulado para clientes de correo que lo soportan */
        .btn-custom:hover {
            background-color: #084298; 
        }
        .footer {
            margin-top: 25px;
            font-size: 11px;
            color: #999;
            text-align: center;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-body">
            <img src="<?php echo e(asset('img/logo_new.png')); ?>" alt="VetSync" class="logo">
            
            <h3>BIENVENIDO A VETSYNC</h3>
            
            <p>
                Hola <strong><?php echo e($user->name); ?></strong>, estamos listos para ayudarte con la gestión de tus servicios. 
                Para activar todas las funciones de tu cuenta, confirma tu dirección de correo electrónico.
            </p>

            <a href="<?php echo e($url); ?>" class="btn-custom">
                Verificar mi cuenta
            </a>

            <p style="margin-top: 35px; font-size: 13px; color: #a0aec0;">
                Este es un correo automático. Si no solicitaste este registro, puedes borrarlo con seguridad.
            </p>
        </div>
    </div>
    
    <div class="footer">
        &copy; <?php echo e(date('Y')); ?> VETSYNC - GESTIÓN INTEGRAL
    </div>
</div>

</body>
</html><?php /**PATH C:\laragon\www\inventario\resources\views/emails/bienvenida.blade.php ENDPATH**/ ?>
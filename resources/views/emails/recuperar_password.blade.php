<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0a2f6b; /* El azul oscuro de VetSync */
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 40px;
            text-align: center;
            color: #333333;
            line-height: 1.6;
        }
        .btn-container {
            margin: 30px 0;
        }
        .btn {
            background-color: #0a2f6b;
            color: #ffffff !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            background-color: #f9f9f9;
            color: #777777;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>VetSync</h1>
        </div>
        <div class="content">
            <h2>Restablecer Contraseña</h2>
            <p>Hola,</p>
            <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en el sistema de gestión veterinaria **VetSync**.</p>
            <p>Si fuiste tú, haz clic en el botón de abajo para elegir una nueva clave:</p>
            
            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Cambiar mi Contraseña</a>
            </div>
            
            <p>Este enlace de recuperación expirará pronto por seguridad.</p>
        </div>
        <div class="footer">
            <p>Si no solicitaste este cambio, puedes ignorar este correo de forma segura.</p>
            <p>&copy; {{ date('Y') }} VetSync - Software Veterinario.</p>
        </div>
    </div>
</body>
</html>
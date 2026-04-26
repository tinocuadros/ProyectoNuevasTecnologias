<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: url('/img/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            top: 0;
            left: 0;
            z-index: 1;
        }

        .login-box {
            position: relative;
            z-index: 2;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }

        .login-title {
            font-weight: 700;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .links a {
            font-size: 0.9rem;
        }

        .mb-3 label {
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="overlay"></div>

    <div class="login-box text-center">

        <!-- Logo -->
        <div class="mb-3">
            
            <h4 class="login-title mb-0">INVENTARIO</h4>
            <small class="text-muted">Sistema de Inventario y Ventas</small>
        </div>

        <hr>

        <form action="{{ route('login.post') }}" method="POST" class="mt-3">
    
            @csrf

            <div class="mb-3 text-start">
                <label class="form-label">Correo</label>
                <input type="text" name="username" class="form-control" placeholder="usuario@correo.com" required>
            </div>

            <div class="mb-3 text-start">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button class="btn btn-success w-100 py-2 fw-semibold">Ingresar</button>

            
            <!-- Mensajes -->
@if($errors->any())
    <div class="alert alert-danger mt-3">
        {{ $errors->first() }}
    </div>
@endif

@if(session('status'))
    <div class="alert alert-success mt-3">
        {{ session('status') }}
    </div>
@endif

            <!-- Links -->
            <div class="links mt-3">
                <a th:href="{{url('')}}" class="btn btn-link text-decoration-none">
                    <i class="bi bi-person-plus"></i> Registrar nuevo usuario
                </a>
                <br>
                <a th:href="@{/forgot_password}" class="btn btn-link text-decoration-none text-success">
                    <i class="bi bi-key"></i> ¿Olvidaste tu contraseña?
                </a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

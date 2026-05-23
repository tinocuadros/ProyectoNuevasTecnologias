<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 400px;">

    <h3 class="mb-4">Crear cuenta</h3>
    @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    @endif
    <form method="POST" action="{{ url('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" class="form-control" autocomplete="name" required>
        </div>

        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" autocomplete="email" required>
        </div>

        <div class="mb-3">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" autocomplete="new-password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password" required>
        </div>

        <button class="btn bg-custom-btn w-100">Registrarse</button>
        <div class="text-center d-flex justify-content-between mt-2">
            <a href="{{ url('login') }}" class="text-decoration-none small">Regresar al Login</a>
            
        </div>
        

    </form>

</div>

</body>
</html>
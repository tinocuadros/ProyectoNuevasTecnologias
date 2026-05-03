<!DOCTYPE html>
<html>
<head>
    <title>Nueva Contraseña - VetSync</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 450px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="text-center mb-4">Nueva Contraseña</h3>
            
            <form method="POST" action="{{ url('resetear-password') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-floating-custom mt-4 mb-4">
                    <input type="password" name="password" class="form-control-custom" placeholder=" " required>
                    <label class="label-custom">Contraseña Nueva</label>
                </div>

                <div class="form-floating-custom mt-4 mb-4">
                    <input type="password" name="password_confirmation" class="form-control-custom" placeholder=" " required>
                    <label class="label-custom">Confirmar Contraseña</label>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger small">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button class="btn bg-custom-btn w-100" id="btnSubmit">Cambiar Contraseña</button>
            </form>
        </div>
    </div>
</div>
</body>

<script>
    document.querySelector('form').onsubmit = function() {
        let btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Actualizando...';
        btn.disabled = true;
    };
</script>
</script>
</html>
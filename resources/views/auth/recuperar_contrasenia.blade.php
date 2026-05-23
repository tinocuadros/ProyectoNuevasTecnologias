<!DOCTYPE html>
<html>
<head>
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 450px;">

    <div class="card shadow">
        <div class="card-body">
            <h3 class="mb-4 text-center">Recuperar la contraseña de su cuenta</h3>
                <div class="text-center">
                    <img src="{{ asset('img/logo_new.png') }}" alt="" class="img-fluid" style="max-height: 100px;">
                </div>
                @if (session('message'))
                    <div class="alert bg-custom text-white">
                        {{ session('message') }}
                    </div>
                @endif
                <form method="POST" action="{{ url('recuperar_contrasenia') }}">
                    @csrf

                    

                     <div class="form-floating-custom mt-4 mb-4">
                        <input type="email" name="email" class="form-control-custom" placeholder=" " required>
                        <label class="label-custom">Correo electrónico</label>
                    </div>

                    <p class="small text-muted mt-2">
                        Enviaremos un correo a la dirección que escriba aquí con la contraseña, siempre y cuando la dirección de correo esté registrada en el sistema.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button class="btn bg-custom-btn w-100">Recuperar</button>
                </form>
        </div>
    </div>

</div>

</body>
</html>
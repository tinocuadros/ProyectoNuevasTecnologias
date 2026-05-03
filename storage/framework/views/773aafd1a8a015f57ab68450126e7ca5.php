<!DOCTYPE html>
<html>
<head>
    <title>Verificar Correo - VetSync</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow">
            <div class="card-body text-center">
                <h3>¡Casi listo, <?php echo e(auth()->user()->name); ?>!</h3>
                <p class="mt-3">
                    Gracias por registrarte en <strong>VetSync</strong>. Por favor, verifica tu correo electrónico haciendo clic en el enlace que acabamos de enviarte.
                </p>

                <?php if(session('message')): ?>
                    <div class="alert bg-custom text-white">
                        <?php echo e(session('message')); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary w-100">Reenviar correo de verificación</button>
                </form>

                <form method="POST" action="<?php echo e(url('/logout')); ?>" class="mt-3">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-link text-muted">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\inventario\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>
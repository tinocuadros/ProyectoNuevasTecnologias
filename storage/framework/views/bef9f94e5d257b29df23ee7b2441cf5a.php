<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/estilos.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">



<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <!-- El login ocupará 11 columnas en móvil, 6 en tablet y 4 en desktop -->
    <div class="col-11 col-sm-8 col-md-6 col-lg-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
                <h3 class="mb-4 text-center fw-bold">Iniciar sesión</h3>
                
                <div class="text-center mb-4">
                    <img src="<?php echo e(asset('img/logo_new.png')); ?>" alt="Logo" class="img-fluid" style="max-height: 80px;">
                </div>

                <?php if(session('message')): ?>
                    <div class="alert alert-success border-0 shadow-sm mb-4 small">
                        <i class="bi bi-check-circle-fill me-2"></i> <?php echo e(session('message')); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(url('login')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-floating-custom mb-4">
                        <input type="text" name="email" class="form-control-custom w-100" placeholder=" " autocomplete="off" required>
                        <label class="label-custom">Correo o Username</label>
                    </div>

                    <div class="form-floating-custom mb-3">
                        <input type="password" name="password" class="form-control-custom w-100" autocomplete="off" placeholder=" " required>
                        <label class="label-custom">Contraseña</label>
                    </div>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger py-2 small">
                            <?php echo e($errors->first()); ?>

                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn bg-custom-btn w-100 text-white py-2 fw-bold mt-3 shadow-sm">
                        Entrar
                    </button>

                    <div class="text-center mt-4">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="<?php echo e(url('recuperar_contrasenia')); ?>" class="text-decoration-none small text-muted">¿Olvidó su contraseña?</a>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html><?php /**PATH C:\laragon\www\inventario\resources\views/auth/login.blade.php ENDPATH**/ ?>
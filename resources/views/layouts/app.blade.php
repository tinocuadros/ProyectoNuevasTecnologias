<!DOCTYPE html>
<html>
<head>
<<<<<<< HEAD
=======
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
    <title>Mi App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <!-- 🔝 Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
        <div class="container-fluid">
            
            <a class="navbar-brand" href="{{ url('/')}}">
                <div class="text-center">
<<<<<<< HEAD
                    Inventario
=======
                    <img src="{{ asset('img/logo_new.png') }}" alt="" class="d-inline-block align-top" style="max-height: 40px; filter: brightness(0) invert(1);">
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
                </div>
            </a>
                <!-- 🔽 IZQUIERDA: Cuenta -->

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Administración
                </a>
                <ul class="dropdown-menu dropdown-menu-custom">
<<<<<<< HEAD
                    <li><a class="dropdown-item" href="{{ route ('usuarios.index')}}">Usuarios</a></li>
                    <li><a class="dropdown-item" href="{{url('/perfiles')}}">Perfiles de Usuario</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
=======
                    <li><a class="dropdown-item" href="{{url('/admin/usuarios')}}">Usuarios</a></li>
                    <li><a class="dropdown-item" href="{{url('/perfiles')}}">Perfiles de Usuario</a></li>
                    <li><a class="dropdown-item" href="{{url('/permisos')}}">Permisos</a></li>
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
                </ul>
                </li>

                <li class="nav-item dropdown">
                <a class="nav-link text-white dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Módulos
                </a>
                <ul class="dropdown-menu  dropdown-menu-custom">
<<<<<<< HEAD
                    <li><a class="dropdown-item" href="{{ route('productos.index')}}">Productos</a></li>
                   
=======
                    <li><a class="dropdown-item" href="{{url('/ventas')}}"">Facturacion</a></li>
                    <li><a class="dropdown-item" href="{{url('/clientes')}}">Clientes</a></li>
                    <li><a class="dropdown-item" href="{{url('/proveedores')}}">Proveedores</a></li>
                    <li><a class="dropdown-item" href="{{url('/inventario')}}">Inventario</a></li>
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8
                </ul>
                </li>
                
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">

                    <a class="nav-link text-white dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->username ?? 'Cuenta' }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                        <li><a class="dropdown-item" href="{{ url('/perfil') }}">Mi perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Cerrar sesión
                            </a>
                        </li>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>

                </li>
            </ul>
            </div>
        </div>
    </nav>

    <!-- 📦 Contenido dinámico -->
<<<<<<< HEAD
    @yield('content')
=======
    @yield('contenido')
>>>>>>> 47aa1f7fd04f263e86226a1b5e70f164cb6705a8

    <!-- Bootstrap JS (SOLO ESTE) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@yield('scripts')

</body>
</html>
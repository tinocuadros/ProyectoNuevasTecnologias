<?php $__env->startSection('contenido'); ?>
<style>
    .formulario-usuario .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .tabla-usuarios tbody tr {
        line-height: 1;
    }
    .tabla-usuarios tbody tr:hover {
        background-color: #f8f9fa;
    }
    .tabla-usuarios td {
        padding-top: 4px;
        padding-bottom: 4px;
        font-size: 13px;
    }
    .tabla-usuarios th {
        font-size: 13px;
    }
    .busqueda-input {
        border-radius: 20px;
        padding-left: 15px;
    }
    .tabla-usuarios .btn-tooltip {
        position: relative;
        cursor: pointer;
    }
    .tabla-usuarios .btn-tooltip::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 120%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        z-index: 100;
    }
    .tabla-usuarios .btn-tooltip:hover::after {
        opacity: 1;
        visibility: visible;
    }
    .filtro-input-container {
        position: relative;
        height: 36px;
        margin-bottom: 0;
    }
    .filtro-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding: 6px 10px;
        border: 1px solid #dadce0;
        border-radius: 4px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        background: transparent;
    }
    .filtro-input:focus {
        border-color: #0a2f6b;
        box-shadow: 0 0 0 0.5px #0a2f6b;
    }
    .filtro-label {
        position: absolute;
        left: 8px;
        top: 8px;
        padding: 0 2px;
        color: #5f6368;
        font-size: 14px;
        transition: all 0.2s ease;
        pointer-events: none;
        background: white;
    }
    .filtro-input:focus + .filtro-label,
    .filtro-input:not(:placeholder-shown) + .filtro-label {
        top: -8px;
        left: 6px;
        font-size: 11px;
        color: #0a2f6b;
        font-weight: 500;
    }
    .filtro-select-container {
        position: relative;
        height: 36px;
        margin-bottom: 0;
    }
    .filtro-select {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding: 6px 10px;
        border: 1px solid #dadce0;
        border-radius: 4px;
        font-size: 14px;
        outline: none;
        background: white;
        cursor: pointer;
    }
    .filtro-select:focus {
        border-color: #0a2f6b;
        box-shadow: 0 0 0 0.5px #0a2f6b;
    }
</style>
<div class="container-fluid py-4">
    <!-- Filtros y Crear Usuario -->
    <div class="card mb-3">
        <div class="card-header bg-custom text-white py-1">
            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Listado de Usuarios</h6>
        </div>
        <div class="card-body py-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-1">
                    <div class="filtro-input-container">
                        <input type="text" class="filtro-input" id="filtroCedula" placeholder=" " autocomplete="off">
                        <label class="filtro-label">Cédula</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="filtro-input-container">
                        <input type="text" class="filtro-input" id="filtroNombre" placeholder=" " autocomplete="off">
                        <label class="filtro-label">Nombre</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="filtro-input-container">
                        <input type="text" class="filtro-input" id="filtroUsername" placeholder=" " autocomplete="off">
                        <label class="filtro-label">Username</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="filtro-input-container">
                        <input type="text" class="filtro-input" id="filtroEmail" placeholder=" " autocomplete="off">
                        <label class="filtro-label">Email</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="filtro-select-container">
                        <select class="filtro-select" id="filtroEstado">
                            <option value="">Estado</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="filtro-select-container">
                        <select class="filtro-select" id="filtroRol">
                            <option value="">Rol</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <span id="loadingIndicator" class="spinner-border spinner-border-sm text-secondary me-2" style="display:none;"></span>
                    <button class="btn bg-custom-btn btn-sm" style="height:36px;padding-top:4px;padding-bottom:4px;" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
                        <i class="bi bi-plus-circle me-1"></i> Crear Usuario
                    </button>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="table-responsive">
                <table class="table tabla-usuarios table-hover">
                    <thead class="bg-custom text-white">
                        <tr>
                            <th>ID</th>
                            <th>Cédula</th>
                            <th>Nombre Completo</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaUsuariosBody">
                    </tbody>
                </table>
            </div>
            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                <nav>
                    <ul class="pagination" id="paginacionUsuarios">
                    </ul>
                </nav>
            </div>
            </div>
    </div>

</div>

<!-- Modal Crear Usuario -->
<div class="modal fade" id="crearUsuarioModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i> Crear Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioCrearUsuario">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" name="cedula" placeholder="V-12345678">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required placeholder="juanperez">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="primer_nombre" class="form-label">Primer Nombre</label>
                            <input type="text" class="form-control" name="primer_nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                            <input type="text" class="form-control" name="segundo_nombre">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="primer_apellido" class="form-label">Primer Apellido</label>
                            <input type="text" class="form-control" name="primer_apellido" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" name="segundo_apellido">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn bg-custom-btn">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Editar Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioEditarUsuario">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="id" id="editId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editCedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" name="cedula" id="editCedula">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="editUsername" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editPrimerNombre" class="form-label">Primer Nombre</label>
                            <input type="text" class="form-control" name="primer_nombre" id="editPrimerNombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editSegundoNombre" class="form-label">Segundo Nombre</label>
                            <input type="text" class="form-control" name="segundo_nombre" id="editSegundoNombre">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editPrimerApellido" class="form-label">Primer Apellido</label>
                            <input type="text" class="form-control" name="primer_apellido" id="editPrimerApellido" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editSegundoApellido" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" name="segundo_apellido" id="editSegundoApellido">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="editEmail" required>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn bg-custom-btn">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asignar Roles -->
<div class="modal fade" id="asignarRolesModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-person-badge me-2"></i> Asignar Roles</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="usuarioRolesId">
                <div id="listaRolesCheckboxes">
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn bg-custom-btn" onclick="guardarRoles()">Guardar Roles</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Usuario -->
<div class="modal fade" id="detalleUsuarioModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-person-circle me-2"></i> Detalle del Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleUsuarioBody">
                <!-- Se llena dinámicamente -->
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
<script>
var timeoutBusqueda;

var timeoutBusqueda;

$(document).ready(function() {
    cargarUsuarios();
    cargarRoles();

    $('#filtroCedula, #filtroNombre, #filtroUsername, #filtroEmail').on('keyup', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            cargarUsuarios(1);
        }, 300);
    });

    $('#filtroEstado, #filtroRol').on('change', function() {
        cargarUsuarios(1);
    });

    $('#FormularioCrearUsuario').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize() + '&_token=' + '<?php echo e(csrf_token()); ?>';
        
        $.ajax({
            url: '<?php echo e(url("/api/admin/usuarios")); ?>',
            method: 'POST',
            data: formData,
            success: function(data) {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Usuario creado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                    $('#crearUsuarioModal').modal('hide');
                    $('#FormularioCrearUsuario')[0].reset();
                    cargarUsuarios();
                }
            },
            error: function(xhr) {
                console.log(xhr);
                var mensaje = 'Error al crear usuario';
                try {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.email) mensaje = errors.email[0];
                        else if (errors.username) mensaje = errors.username[0];
                    }
                } catch(e) {
                    mensaje = 'Error ' + xhr.status + ': ' + xhr.statusText;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje,
                    confirmButtonColor: '#1a5c1a'
                });
            }
        });
    });

    $('#FormularioEditarUsuario').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editId').val();
        
        var formData = $(this).serialize() + '&_method=PUT&_token=' + '<?php echo e(csrf_token()); ?>';
        
        $.ajax({
            url: '<?php echo e(url("/api/admin/usuarios")); ?>/' + id,
            method: 'POST',
            data: formData,
            success: function(data) {
                if(data.success) {
                    $('#editarUsuarioModal').modal('hide');
                    cargarUsuarios();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Usuario actualizado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            }
        });
    });
});

function cargarUsuarios(pagina = 1) {
    var params = new URLSearchParams();
    params.append('page', pagina);
    
    var cedula = $('#filtroCedula').val();
    var nombre = $('#filtroNombre').val();
    var username = $('#filtroUsername').val();
    var email = $('#filtroEmail').val();
    var estado = $('#filtroEstado').val();
    var rol = $('#filtroRol').val();
    
    if (cedula) params.append('cedula', cedula);
    if (nombre) params.append('nombre', nombre);
    if (username) params.append('username', username);
    if (email) params.append('email', email);
    if (estado !== '') params.append('activo', estado);
    if (rol) params.append('rol_id', rol);
    
    $('#loadingIndicator').show();
    
    $.get('<?php echo e(url("/api/admin/usuarios")); ?>?' + params.toString(), function(data) {
        $('#loadingIndicator').hide();
        var currentUserId = data.current_user_id;
        var activeUserIds = data.active_user_ids || [];
        var html = '';
        if (data.usuarios.data.length === 0) {
            html = '<tr><td colspan="7" class="text-center text-muted">No se encontraron resultados para la búsqueda</td></tr>';
        } else {
            data.usuarios.data.forEach(function(usuario) {
                var nombreCompleto = usuario.primer_nombre + ' ' + (usuario.segundo_nombre || '') + ' ' + usuario.primer_apellido + ' ' + (usuario.segundo_apellido || '');
                var estaEnLinea = activeUserIds.indexOf(usuario.id) !== -1;
                var estadoTexto = estaEnLinea
                    ? 'En línea'
                    : (usuario.activo 
                        ? (usuario.ultimo_login ? 'Desconectado' : 'Nunca')
                        : 'Inactivo');
                var estadoIcono = estaEnLinea
                    ? '<i class="bi bi-wifi text-success" title="En línea"></i>'
                    : (usuario.activo 
                        ? '<i class="bi bi-circle text-secondary" title="' + (usuario.ultimo_login ? 'Último login: ' + new Date(usuario.ultimo_login).toLocaleString() : 'Nunca ha iniciado sesión') + '"></i>'
                        : '<i class="bi bi-x-circle text-danger" title="Inactivo"></i>');
                
                html += '<tr>';
                html += '<td>' + usuario.id + '</td>';
                html += '<td>' + (usuario.cedula || '-') + '</td>';
                html += '<td>' + nombreCompleto.trim() + '</td>';
                html += '<td>' + usuario.username + '</td>';
                html += '<td>' + usuario.email + '</td>';
                html += '<td>' + estadoIcono + ' ' + estadoTexto + '</td>';
                html += '<td class="text-nowrap text-center">';
                html += '<button class="btn btn-sm btn-info py-0 px-1 me-1 btn-tooltip" data-tooltip="Ver Detalle" onclick="verDetalle(' + usuario.id + ')"><i class="bi bi-eye" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-primary py-0 px-1 me-1 btn-tooltip" data-tooltip="Editar" onclick="editarUsuario(' + usuario.id + ')"><i class="bi bi-pencil" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-warning py-0 px-1 me-1 btn-tooltip" data-tooltip="Asignar Roles" onclick="asignarRoles(' + usuario.id + ')"><i class="bi bi-person-badge" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm py-0 px-1 me-1 btn-tooltip ' + (usuario.activo ? 'btn-secondary' : 'btn-success') + '" data-tooltip="' + (usuario.activo ? 'Inactivar' : 'Activar') + '" onclick="toggleActivo(' + usuario.id + ', \'' + usuario.username + '\')"><i class="bi ' + (usuario.activo ? 'bi-person-dash' : 'bi-person-plus') + '" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-danger py-0 px-1 btn-tooltip" data-tooltip="Eliminar" onclick="eliminarUsuario(' + usuario.id + ', \'' + usuario.username + '\')"><i class="bi bi-trash" style="font-size:12px"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
        }
        $('#tablaUsuariosBody').html(html);

        var paginationHtml = '';
        if (data.usuarios.prev_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarUsuarios(' + (pagina - 1) + ')">Anterior</a></li>';
        }
        for (var i = 1; i <= data.usuarios.last_page; i++) {
            paginationHtml += '<li class="page-item ' + (i === pagina ? 'active' : '') + '"><a class="page-link" href="#" onclick="cargarUsuarios(' + i + ')">' + i + '</a></li>';
        }
        if (data.usuarios.next_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarUsuarios(' + (pagina + 1) + ')">Siguiente</a></li>';
        }
        $('#paginacionUsuarios').html(paginationHtml);
    }).fail(function() {
        $('#loadingIndicator').hide();
    });
}

function cargarRoles() {
    $.get('<?php echo e(url("/api/admin/roles")); ?>', function(data) {
        window.rolesData = data.roles;
        var html = '<option value="">Todos</option>';
        data.roles.forEach(function(rol) {
            html += '<option value="' + rol.id + '">' + rol.nombre + '</option>';
        });
        $('#filtroRol').html(html);
    });
}

function verDetalle(id) {
    $.get('<?php echo e(url("/api/admin/usuario")); ?>/' + id, function(data) {
        if (data.user) {
            var usuario = data.user;
            var nombreCompleto = usuario.primer_nombre + ' ' + (usuario.segundo_nombre || '') + ' ' + usuario.primer_apellido + ' ' + (usuario.segundo_apellido || '');
            var rolesHtml = usuario.roles && usuario.roles.length > 0 
                ? usuario.roles.map(function(r) { return '<span class="badge bg-custom me-1">' + r.nombre + '</span>'; }).join('')
                : '<span class="text-muted">Sin roles asignados</span>';
            
            var estadoIcono = data.is_online 
                ? '<i class="bi bi-wifi text-success"></i>'
                : (!usuario.activo 
                    ? '<i class="bi bi-x-circle text-danger"></i>'
                    : '<i class="bi bi-circle text-secondary"></i>');
            var estadoTexto = data.is_online 
                ? 'En línea' 
                : (!usuario.activo 
                    ? 'Inactivo' 
                    : (usuario.ultimo_login ? 'Desconectado' : 'Nunca'));
            var estadoDetalle = estadoIcono + ' ' + estadoTexto;
            if (!data.is_online && usuario.activo && usuario.ultimo_login) {
                estadoDetalle += ' - Último inicio: ' + new Date(usuario.ultimo_login).toLocaleString();
            }
            
            var verificadoHtml = usuario.email_verified_at 
                ? '<i class="bi bi-check-circle text-success"></i> Verificado (' + new Date(usuario.email_verified_at).toLocaleString() + ')'
                : '<i class="bi bi-x-circle text-danger"></i> No verificado';
            
            var html = '<div class="row">';
            html += '<div class="col-md-6 mb-3"><strong>Cédula:</strong><br>' + (usuario.cedula || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Username:</strong><br>' + usuario.username + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Nombre Completo:</strong><br>' + nombreCompleto.trim() + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Email:</strong><br>' + usuario.email + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Verificación:</strong><br>' + verificadoHtml + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Estado:</strong><br>' + estadoDetalle + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Roles Asignados:</strong><br>' + rolesHtml + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Fecha de Creación:</strong><br>' + new Date(usuario.created_at).toLocaleString() + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Última Actualización:</strong><br>' + new Date(usuario.updated_at).toLocaleString() + '</div>';
            html += '</div>';
            
            $('#detalleUsuarioBody').html(html);
            $('#detalleUsuarioModal').modal('show');
        }
    });
}

function editarUsuario(id) {
    $.get('<?php echo e(url("/api/admin/usuario")); ?>/' + id, function(data) {
        if (data.user) {
            var usuario = data.user;
            $('#editId').val(usuario.id);
            $('#editCedula').val(usuario.cedula || '');
            $('#editUsername').val(usuario.username);
            $('#editPrimerNombre').val(usuario.primer_nombre);
            $('#editSegundoNombre').val(usuario.segundo_nombre || '');
            $('#editPrimerApellido').val(usuario.primer_apellido);
            $('#editSegundoApellido').val(usuario.segundo_apellido || '');
            $('#editEmail').val(usuario.email);
            $('#editarUsuarioModal').modal('show');
        }
    });
}

function asignarRoles(id) {
    $('#usuarioRolesId').val(id);
    
    $.get('<?php echo e(url("/api/admin/usuario")); ?>/' + id, function(data) {
        var usuario = data.user;
        var rolesUsuario = usuario.roles ? usuario.roles.map(function(r) { return r.id; }) : [];
        
        var html = '';
        if (window.rolesData) {
            window.rolesData.forEach(function(rol) {
                var checked = rolesUsuario.indexOf(rol.id) !== -1 ? 'checked' : '';
                html += '<div class="form-check">';
                html += '<input class="form-check-input" type="checkbox" value="' + rol.id + '" id="rol_' + rol.id + '" ' + checked + '>';
                html += '<label class="form-check-label" for="rol_' + rol.id + '">' + rol.nombre + '</label>';
                html += '</div>';
            });
        }
        
        if (html === '') {
            html = '<p class="text-muted">No hay roles disponibles. Crear primero.</p>';
        }
        
        $('#listaRolesCheckboxes').html(html);
        $('#asignarRolesModal').modal('show');
    });
}

function guardarRoles() {
    var id = $('#usuarioRolesId').val();
    var roles = [];
    $('#listaRolesCheckboxes input:checked').each(function() {
        roles.push($(this).val());
    });

    $.ajax({
        url: '<?php echo e(url("/api/admin/usuario")); ?>/' + id + '/roles',
        method: 'POST',
        data: JSON.stringify({ roles: roles }),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        success: function(data) {
            if (data.success) {
                $('#asignarRolesModal').modal('hide');
                cargarUsuarios();
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Roles actualizados correctamente',
                    confirmButtonColor: '#1a5c1a'
                });
            }
        }
    });
}

function toggleActivo(id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas cambiar el estado de " + nombre + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1a5c1a',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo e(url("/api/admin/usuario")); ?>/' + id + '/toggle-activo',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                success: function(data) {
                    if (data.success) {
                        cargarUsuarios();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.activo ? nombre + ' activado' : nombre + ' inactivado',
                            confirmButtonColor: '#1a5c1a'
                        });
                    }
                }
            });
        }
    });
}

function eliminarUsuario(id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar a " + nombre + "? No podrás revertir esto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo e(url("/api/admin/usuarios")); ?>/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                success: function(data) {
                    cargarUsuarios();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'El usuario ha sido eliminado',
                        confirmButtonColor: '#1a5c1a'
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar el usuario',
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            });
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ProyectoNuevasTecnologias\ProyectoNuevasTecnologias\resources\views/administracion/usuarios.blade.php ENDPATH**/ ?>
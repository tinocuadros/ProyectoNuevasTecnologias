<?php $__env->startSection('contenido'); ?>
<style>
    .formulario-rol .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .tabla-roles tbody tr {
        line-height: 1;
    }
    .tabla-roles tbody tr:hover {
        background-color: #f8f9fa;
    }
    .tabla-roles td {
        padding-top: 4px;
        padding-bottom: 4px;
        font-size: 13px;
    }
    .tabla-roles th {
        font-size: 13px;
    }
    .tabla-roles .btn-tooltip {
        position: relative;
        cursor: pointer;
    }
    .tabla-roles .btn-tooltip::after {
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
    .tabla-roles .btn-tooltip:hover::after {
        opacity: 1;
        visibility: visible;
    }
</style>
<div class="container-fluid py-4">
    <div class="row">
        <!-- Izquierda: Formulario de registro -->
        <div class="col-md-4">
            <div class="formulario-rol">
                <div class="card">
                    <div class="card-header bg-custom text-white">
                        <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Crear Perfil</h5>
                    </div>
                    <div class="card-body">
                        <form id="FormularioRegistroRol">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Perfil</label>
                                <input type="text" class="form-control" name="nombre" autocomplete="off" placeholder="Ej: Administrador" required>
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" name="slug" autocomplete="off" placeholder="Ej: admin" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" name="descripcion" rows="3" placeholder="Descripción del perfil"></textarea>
                            </div>
                            <button type="submit" class="btn bg-custom-btn w-100">
                                <i class="bi bi-check-lg me-1"></i> Guardar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Derecha: Listado -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-custom text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Listado de Perfiles</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tabla-roles table-hover">
                            <thead class="bg-custom text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Slug</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaRolesBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Rol -->
<div class="modal fade" id="editarRolModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Perfil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioEditarRol">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label for="editNombre" class="form-label">Nombre del Perfil</label>
                        <input type="text" class="form-control" name="nombre" id="editNombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSlug" class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug" id="editSlug" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="editDescripcion" rows="3"></textarea>
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

<!-- Modal Asignar Permisos -->
<div class="modal fade" id="asignarPermisosModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-shield me-2"></i> Asignar Permisos al Perfil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rolPermisosId">
                <div id="listaModulosPermisos"></div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn bg-custom-btn" onclick="guardarPermisos()">Guardar Permisos</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    cargarRoles();

    $('#FormularioRegistroRol').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?php echo e(url("/api/admin/roles")); ?>',
            method: 'POST',
            data: $(this).serialize(),
            success: function(data) {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Perfil creado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                    $('#FormularioRegistroRol')[0].reset();
                    cargarRoles();
                }
            }
        });
    });

    $('#FormularioEditarRol').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editId').val();
        
        $.ajax({
            url: '<?php echo e(url("/api/admin/roles")); ?>/' + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(data) {
                if(data.success) {
                    $('#editarRolModal').modal('hide');
                    cargarRoles();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Perfil actualizado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            }
        });
    });

    function cargarRoles() {
        $.get('<?php echo e(url("/api/admin/roles")); ?>', function(data) {
            var html = '';
            data.roles.forEach(function(rol) {
                html += '<tr>';
                html += '<td>' + rol.id + '</td>';
                html += '<td>' + rol.nombre + '</td>';
                html += '<td>' + rol.slug + '</td>';
                html += '<td>' + (rol.descripcion || '-') + '</td>';
html += '<td class="text-nowrap text-center">';
                html += '<button class="btn btn-sm btn-warning py-0 px-1 me-1 btn-tooltip" data-tooltip="Asignar Permisos" onclick="asignarPermisos(' + rol.id + ')"><i class="bi bi-shield" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-primary py-0 px-1 me-1 btn-tooltip" data-tooltip="Editar" onclick="editarRol(' + rol.id + ')"><i class="bi bi-pencil" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-danger py-0 px-1 btn-tooltip" data-tooltip="Eliminar" onclick="eliminarRol(' + rol.id + ')"><i class="bi bi-trash" style="font-size:12px"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
            $('#tablaRolesBody').html(html);
        });
    }

    window.editarRol = function(id) {
        $.get('<?php echo e(url("/api/admin/roles")); ?>/' + id, function(data) {
            $('#editId').val(data.rol.id);
            $('#editNombre').val(data.rol.nombre);
            $('#editSlug').val(data.rol.slug);
            $('#editDescripcion').val(data.rol.descripcion || '');
            $('#editarRolModal').modal('show');
        });
    };

    window.eliminarRol = function(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo e(url("/api/admin/roles")); ?>/' + id,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(data) {
                        cargarRoles();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'El perfil ha sido eliminado',
                            confirmButtonColor: '#1a5c1a'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar el perfil',
                            confirmButtonColor: '#1a5c1a'
                        });
                    }
                });
            }
});
    }
});

var permisosData = [];

function asignarPermisos(id) {
    $('#rolPermisosId').val(id);
    
    if (permisosData.length === 0) {
        $.get('<?php echo e(url("/api/admin/permisos")); ?>', function(data) {
            permisosData = data.permisos;
            mostrarPermisosModal(id);
        });
    } else {
        mostrarPermisosModal(id);
    }
}

function mostrarPermisosModal(id) {
    $.get('<?php echo e(url("/api/admin/roles")); ?>/' + id, function(data) {
        var rol = data.rol;
        var permisosRol = rol.permisos ? rol.permisos.map(function(p) { return p.id; }) : [];
        
        var modulos = {};
        permisosData.forEach(function(permiso) {
            var modulo = permiso.modulo || 'Sin módulo';
            if (!modulos[modulo]) {
                modulos[modulo] = [];
            }
            modulos[modulo].push(permiso);
        });
        
        var html = '<div class="row">';
        for (var modulo in modulos) {
            html += '<div class="col-md-6 mb-3">';
            html += '<h6 class="border-bottom pb-1 mb-2">' + modulo + '</h6>';
            modulos[modulo].forEach(function(permiso) {
                var checked = permisosRol.indexOf(permiso.id) !== -1 ? 'checked' : '';
                html += '<div class="form-check">';
                html += '<input class="form-check-input" type="checkbox" value="' + permiso.id + '" id="permiso_' + permiso.id + '" ' + checked + '>';
                html += '<label class="form-check-label" for="permiso_' + permiso.id + '">' + permiso.nombre + '</label>';
                html += '</div>';
            });
            html += '</div>';
        }
        html += '</div>';
        
        if (Object.keys(modulos).length === 0) {
            html = '<p class="text-muted">No hay permisos disponibles. Crear primero.</p>';
        }
        
        $('#listaModulosPermisos').html(html);
        $('#asignarPermisosModal').modal('show');
    });
}

function guardarPermisos() {
    var id = $('#rolPermisosId').val();
    var permisos = [];
    $('#listaModulosPermisos input:checked').each(function() {
        permisos.push($(this).val());
    });
    
    $.ajax({
        url: '<?php echo e(url("/api/admin/roles")); ?>/' + id + '/permisos',
        method: 'POST',
        data: { permisos: permisos },
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        success: function(data) {
            if (data.success) {
                $('#asignarPermisosModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Permisos actualizados correctamente',
                    confirmButtonColor: '#1a5c1a'
                });
            }
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\inventario\resources\views/administracion/perfiles_usuario.blade.php ENDPATH**/ ?>
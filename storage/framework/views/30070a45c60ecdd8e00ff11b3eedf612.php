<?php $__env->startSection('contenido'); ?>
<style>
    .tabla-proveedores tbody tr {
        line-height: 1;
    }
    .tabla-proveedores tbody tr:hover {
        background-color: #f8f9fa;
    }
    .tabla-proveedores td {
        padding-top: 4px;
        padding-bottom: 4px;
        font-size: 13px;
    }
    .tabla-proveedores th {
        font-size: 13px;
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
    .btn-tooltip {
        position: relative;
        cursor: pointer;
    }
    .btn-tooltip::after {
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
    .btn-tooltip:hover::after {
        opacity: 1;
        visibility: visible;
    }
</style>
<div class="container-fluid py-4">
    <div class="card mb-3">
        <div class="card-header bg-custom text-white py-1">
            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Listado de Proveedores</h6>
        </div>
        <div class="card-body py-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <div class="filtro-input-container">
                        <input type="text" class="filtro-input" id="filtroCodigo" placeholder=" " autocomplete="off">
                        <label class="filtro-label">Código</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filtro-input-container">
                        <input type="text" class="filtro-input" id="filtroNombre" placeholder=" " autocomplete="off">
                        <label class="filtro-label">Nombre</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="filtro-input-container">
                        <input type="text" class="filtro-input" id="filtroRuc" placeholder=" " autocomplete="off">
                        <label class="filtro-label">RUC</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="filtro-select-container">
                        <select class="filtro-select" id="filtroActivo">
                            <option value="">Estado</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <span id="loadingIndicator" class="spinner-border spinner-border-sm text-secondary me-2" style="display:none;"></span>
                    <button class="btn bg-custom-btn btn-sm w-100" style="height:36px;padding-top:4px;padding-bottom:4px;" data-bs-toggle="modal" data-bs-target="#crearProveedorModal">
                        <i class="bi bi-plus-circle me-1"></i> Crear Proveedor
                    </button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="table-responsive">
                    <table class="table tabla-proveedores table-hover">
                        <thead class="bg-custom text-white">
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>RUC</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaProveedoresBody">
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <nav>
                        <ul class="pagination" id="paginacionProveedores">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Proveedor -->
<div class="modal fade" id="crearProveedorModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-truck me-2"></i> Crear Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioCrearProveedor">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" name="codigo" id="codigo" placeholder="PROV-001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ruc" class="form-label">RUC</label>
                            <input type="text" class="form-control" name="ruc" id="ruc">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" id="telefono">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion" id="direccion">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contacto_nombre" class="form-label">Nombre Contacto</label>
                            <input type="text" class="form-control" name="contacto_nombre" id="contacto_nombre">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contacto_telefono" class="form-label">Teléfono Contacto</label>
                            <input type="text" class="form-control" name="contacto_telefono" id="contacto_telefono">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                            <label class="form-check-label" for="activo">Activo</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn bg-custom-btn">Crear Proveedor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Proveedor -->
<div class="modal fade" id="editarProveedorModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Editar Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioEditarProveedor">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="id" id="editId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editCodigo" class="form-label">Código</label>
                            <input type="text" class="form-control" name="codigo" id="editCodigo">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editNombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" name="nombre" id="editNombre" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editRuc" class="form-label">RUC</label>
                            <input type="text" class="form-control" name="ruc" id="editRuc">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" id="editTelefono">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editDireccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion" id="editDireccion">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editContactoNombre" class="form-label">Nombre Contacto</label>
                            <input type="text" class="form-control" name="contacto_nombre" id="editContactoNombre">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editContactoTelefono" class="form-label">Teléfono Contacto</label>
                            <input type="text" class="form-control" name="contacto_telefono" id="editContactoTelefono">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="editActivo" name="activo">
                            <label class="form-check-label" for="editActivo">Activo</label>
                        </div>
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

<!-- Modal Detalle Proveedor -->
<div class="modal fade" id="detalleProveedorModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i> Detalle del Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleProveedorBody">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
var timeoutBusqueda;

$(document).ready(function() {
    cargarProveedores();

    $('#filtroCodigo, #filtroNombre, #filtroRuc').on('keyup', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            cargarProveedores(1);
        }, 300);
    });

    $('#filtroActivo').on('change', function() {
        cargarProveedores(1);
    });

    $('#FormularioCrearProveedor').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize() + '&_token=' + '<?php echo e(csrf_token()); ?>';
        
        $.ajax({
            url: '<?php echo e(url("/api/proveedores")); ?>',
            method: 'POST',
            data: formData,
            success: function(data) {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Proveedor creado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                    $('#crearProveedorModal').modal('hide');
                    $('#FormularioCrearProveedor')[0].reset();
                    $('#activo').prop('checked', true);
                    cargarProveedores();
                }
            },
            error: function(xhr) {
                var mensaje = 'Error al crear proveedor';
                try {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        mensaje = Object.values(errors)[0][0];
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

    $('#FormularioEditarProveedor').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editId').val();
        
        var formData = $(this).serialize() + '&_method=PUT&_token=' + '<?php echo e(csrf_token()); ?>';
        
        $.ajax({
            url: '<?php echo e(url("/api/proveedores")); ?>/' + id,
            method: 'POST',
            data: formData,
            success: function(data) {
                if(data.success) {
                    $('#editarProveedorModal').modal('hide');
                    cargarProveedores();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Proveedor actualizado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            },
            error: function(xhr) {
                var mensaje = 'Error al actualizar proveedor';
                try {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        mensaje = Object.values(errors)[0][0];
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
});

function cargarProveedores(pagina = 1) {
    var params = new URLSearchParams();
    params.append('page', pagina);
    
    var codigo = $('#filtroCodigo').val();
    var nombre = $('#filtroNombre').val();
    var ruc = $('#filtroRuc').val();
    var activo = $('#filtroActivo').val();
    
    if (codigo) params.append('codigo', codigo);
    if (nombre) params.append('nombre', nombre);
    if (ruc) params.append('ruc', ruc);
    if (activo !== '') params.append('activo', activo);
    
    $('#loadingIndicator').show();
    
    $.get('<?php echo e(url("/api/proveedores")); ?>?' + params.toString(), function(data) {
        $('#loadingIndicator').hide();
        
        var html = '';
        if (data.data.length === 0) {
            html = '<tr><td colspan="8" class="text-center text-muted">No se encontraron resultados</td></tr>';
        } else {
            data.data.forEach(function(proveedor) {
                var estadoIcono = proveedor.activo 
                    ? '<i class="bi bi-check-circle text-success" title="Activo"></i>'
                    : '<i class="bi bi-x-circle text-danger" title="Inactivo"></i>';
                var estadoTexto = proveedor.activo ? 'Activo' : 'Inactivo';
                
                html += '<tr>';
                html += '<td>' + proveedor.id + '</td>';
                html += '<td>' + (proveedor.codigo || '-') + '</td>';
                html += '<td>' + proveedor.nombre + '</td>';
                html += '<td>' + (proveedor.ruc || '-') + '</td>';
                html += '<td>' + (proveedor.telefono || '-') + '</td>';
                html += '<td>' + (proveedor.email || '-') + '</td>';
                html += '<td>' + estadoIcono + ' ' + estadoTexto + '</td>';
                html += '<td class="text-nowrap text-center">';
                html += '<button class="btn btn-sm btn-info py-0 px-1 me-1 btn-tooltip" data-tooltip="Ver Detalle" onclick="verDetalle(' + proveedor.id + ')"><i class="bi bi-eye" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-primary py-0 px-1 me-1 btn-tooltip" data-tooltip="Editar" onclick="editarProveedor(' + proveedor.id + ')"><i class="bi bi-pencil" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-danger py-0 px-1 btn-tooltip" data-tooltip="Eliminar" onclick="eliminarProveedor(' + proveedor.id + ', \'' + proveedor.nombre + '\')"><i class="bi bi-trash" style="font-size:12px"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
        }
        $('#tablaProveedoresBody').html(html);

        var paginationHtml = '';
        if (data.prev_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarProveedores(' + (pagina - 1) + ')">Anterior</a></li>';
        }
        for (var i = 1; i <= data.last_page; i++) {
            paginationHtml += '<li class="page-item ' + (i === pagina ? 'active' : '') + '"><a class="page-link" href="#" onclick="cargarProveedores(' + i + ')">' + i + '</a></li>';
        }
        if (data.next_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarProveedores(' + (pagina + 1) + ')">Siguiente</a></li>';
        }
        $('#paginacionProveedores').html(paginationHtml);
    }).fail(function() {
        $('#loadingIndicator').hide();
    });
}

function verDetalle(id) {
    $.get('<?php echo e(url("/api/proveedores")); ?>/' + id, function(data) {
        if (data.proveedor) {
            var proveedor = data.proveedor;
            
            var html = '<div class="row">';
            html += '<div class="col-md-6 mb-3"><strong>Código:</strong><br>' + (proveedor.codigo || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>RUC:</strong><br>' + (proveedor.ruc || 'No registra') + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Nombre:</strong><br>' + proveedor.nombre + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Teléfono:</strong><br>' + (proveedor.telefono || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Email:</strong><br>' + (proveedor.email || 'No registra') + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Dirección:</strong><br>' + (proveedor.direccion || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Contacto:</strong><br>' + (proveedor.contacto_nombre || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Teléfono Contacto:</strong><br>' + (proveedor.contacto_telefono || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Estado:</strong><br>' + (proveedor.activo ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Fecha Creación:</strong><br>' + new Date(proveedor.created_at).toLocaleString() + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Última Actualización:</strong><br>' + new Date(proveedor.updated_at).toLocaleString() + '</div>';
            html += '</div>';
            
            $('#detalleProveedorBody').html(html);
            $('#detalleProveedorModal').modal('show');
        }
    });
}

function editarProveedor(id) {
    $.get('<?php echo e(url("/api/proveedores")); ?>/' + id, function(data) {
        if (data.proveedor) {
            var proveedor = data.proveedor;
            $('#editId').val(proveedor.id);
            $('#editCodigo').val(proveedor.codigo || '');
            $('#editNombre').val(proveedor.nombre);
            $('#editRuc').val(proveedor.ruc || '');
            $('#editTelefono').val(proveedor.telefono || '');
            $('#editEmail').val(proveedor.email || '');
            $('#editDireccion').val(proveedor.direccion || '');
            $('#editContactoNombre').val(proveedor.contacto_nombre || '');
            $('#editContactoTelefono').val(proveedor.contacto_telefono || '');
            $('#editActivo').prop('checked', proveedor.activo);
            $('#editarProveedorModal').modal('show');
        }
    });
}

function eliminarProveedor(id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar a " + nombre + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo e(url("/api/proveedores")); ?>/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                success: function(data) {
                    cargarProveedores();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'El proveedor ha sido eliminado',
                        confirmButtonColor: '#1a5c1a'
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar el proveedor',
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            });
        }
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\inventario\resources\views/proveedores/proveedores.blade.php ENDPATH**/ ?>
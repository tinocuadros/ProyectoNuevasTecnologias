@extends('layouts.app')

@section('contenido')
<style>
    .tabla-clientes tbody tr {
        line-height: 1;
    }
    .tabla-clientes tbody tr:hover {
        background-color: #f8f9fa;
    }
    .tabla-clientes td {
        padding-top: 4px;
        padding-bottom: 4px;
        font-size: 13px;
    }
    .tabla-clientes th {
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
            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Listado de Clientes</h6>
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
                        <input type="text" class="filtro-input" id="filtroCedula" placeholder=" " autocomplete="off">
                        <label class="filtro-label">Cédula</label>
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
                    <button class="btn bg-custom-btn btn-sm w-100" style="height:36px;padding-top:4px;padding-bottom:4px;" data-bs-toggle="modal" data-bs-target="#crearClienteModal">
                        <i class="bi bi-plus-circle me-1"></i> Crear Cliente
                    </button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="table-responsive">
                    <table class="table tabla-clientes table-hover">
                        <thead class="bg-custom text-white">
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaClientesBody">
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <nav>
                        <ul class="pagination" id="paginacionClientes">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Cliente -->
<div class="modal fade" id="crearClienteModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i> Crear Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioCrearCliente">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" name="codigo" id="codigo" placeholder="CLI-001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" name="cedula" id="cedula">
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
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                            <label class="form-check-label" for="activo">Activo</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn bg-custom-btn">Crear Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Cliente -->
<div class="modal fade" id="editarClienteModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Editar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioEditarCliente">
                    @csrf
                    @method('PUT')
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
                            <label for="editCedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" name="cedula" id="editCedula">
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

<!-- Modal Detalle Cliente -->
<div class="modal fade" id="detalleClienteModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i> Detalle del Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleClienteBody">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var timeoutBusqueda;

$(document).ready(function() {
    cargarClientes();

    $('#filtroCodigo, #filtroNombre, #filtroCedula').on('keyup', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            cargarClientes(1);
        }, 300);
    });

    $('#filtroActivo').on('change', function() {
        cargarClientes(1);
    });

    $('#FormularioCrearCliente').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize() + '&_token=' + '{{ csrf_token() }}';
        
        $.ajax({
            url: '{{ url("/api/clientes") }}',
            method: 'POST',
            data: formData,
            success: function(data) {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Cliente creado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                    $('#crearClienteModal').modal('hide');
                    $('#FormularioCrearCliente')[0].reset();
                    $('#activo').prop('checked', true);
                    cargarClientes();
                }
            },
            error: function(xhr) {
                var mensaje = 'Error al crear cliente';
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

    $('#FormularioEditarCliente').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editId').val();
        
        var formData = $(this).serialize() + '&_method=PUT&_token=' + '{{ csrf_token() }}';
        
        $.ajax({
            url: '{{ url("/api/clientes") }}/' + id,
            method: 'POST',
            data: formData,
            success: function(data) {
                if(data.success) {
                    $('#editarClienteModal').modal('hide');
                    cargarClientes();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Cliente actualizado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            },
            error: function(xhr) {
                var mensaje = 'Error al actualizar cliente';
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

function cargarClientes(pagina = 1) {
    var params = new URLSearchParams();
    params.append('page', pagina);
    
    var codigo = $('#filtroCodigo').val();
    var nombre = $('#filtroNombre').val();
    var cedula = $('#filtroCedula').val();
    var activo = $('#filtroActivo').val();
    
    if (codigo) params.append('codigo', codigo);
    if (nombre) params.append('nombre', nombre);
    if (cedula) params.append('cedula', cedula);
    if (activo !== '') params.append('activo', activo);
    
    $('#loadingIndicator').show();
    
    $.get('{{ url("/api/clientes") }}?' + params.toString(), function(data) {
        $('#loadingIndicator').hide();
        
        var html = '';
        if (data.data.length === 0) {
            html = '<tr><td colspan="8" class="text-center text-muted">No se encontraron resultados</td></tr>';
        } else {
            data.data.forEach(function(cliente) {
                var estadoIcono = cliente.activo 
                    ? '<i class="bi bi-check-circle text-success" title="Activo"></i>'
                    : '<i class="bi bi-x-circle text-danger" title="Inactivo"></i>';
                var estadoTexto = cliente.activo ? 'Activo' : 'Inactivo';
                
                html += '<tr>';
                html += '<td>' + cliente.id + '</td>';
                html += '<td>' + (cliente.codigo || '-') + '</td>';
                html += '<td>' + cliente.nombre + '</td>';
                html += '<td>' + (cliente.cedula || '-') + '</td>';
                html += '<td>' + (cliente.telefono || '-') + '</td>';
                html += '<td>' + (cliente.email || '-') + '</td>';
                html += '<td>' + estadoIcono + ' ' + estadoTexto + '</td>';
                html += '<td class="text-nowrap text-center">';
                html += '<button class="btn btn-sm btn-info py-0 px-1 me-1 btn-tooltip" data-tooltip="Ver Detalle" onclick="verDetalle(' + cliente.id + ')"><i class="bi bi-eye" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-primary py-0 px-1 me-1 btn-tooltip" data-tooltip="Editar" onclick="editarCliente(' + cliente.id + ')"><i class="bi bi-pencil" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-danger py-0 px-1 btn-tooltip" data-tooltip="Eliminar" onclick="eliminarCliente(' + cliente.id + ', \'' + cliente.nombre + '\')"><i class="bi bi-trash" style="font-size:12px"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
        }
        $('#tablaClientesBody').html(html);

        var paginationHtml = '';
        if (data.prev_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarClientes(' + (pagina - 1) + ')">Anterior</a></li>';
        }
        for (var i = 1; i <= data.last_page; i++) {
            paginationHtml += '<li class="page-item ' + (i === pagina ? 'active' : '') + '"><a class="page-link" href="#" onclick="cargarClientes(' + i + ')">' + i + '</a></li>';
        }
        if (data.next_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarClientes(' + (pagina + 1) + ')">Siguiente</a></li>';
        }
        $('#paginacionClientes').html(paginationHtml);
    }).fail(function() {
        $('#loadingIndicator').hide();
    });
}

function verDetalle(id) {
    $.get('{{ url("/api/clientes") }}/' + id, function(data) {
        if (data.cliente) {
            var cliente = data.cliente;
            
            var html = '<div class="row">';
            html += '<div class="col-md-6 mb-3"><strong>Código:</strong><br>' + (cliente.codigo || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Cédula:</strong><br>' + (cliente.cedula || 'No registra') + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Nombre:</strong><br>' + cliente.nombre + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Teléfono:</strong><br>' + (cliente.telefono || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Email:</strong><br>' + (cliente.email || 'No registra') + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Dirección:</strong><br>' + (cliente.direccion || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Estado:</strong><br>' + (cliente.activo ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Fecha Creación:</strong><br>' + new Date(cliente.created_at).toLocaleString() + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Última Actualización:</strong><br>' + new Date(cliente.updated_at).toLocaleString() + '</div>';
            html += '</div>';
            
            $('#detalleClienteBody').html(html);
            $('#detalleClienteModal').modal('show');
        }
    });
}

function editarCliente(id) {
    $.get('{{ url("/api/clientes") }}/' + id, function(data) {
        if (data.cliente) {
            var cliente = data.cliente;
            $('#editId').val(cliente.id);
            $('#editCodigo').val(cliente.codigo || '');
            $('#editNombre').val(cliente.nombre);
            $('#editCedula').val(cliente.cedula || '');
            $('#editTelefono').val(cliente.telefono || '');
            $('#editEmail').val(cliente.email || '');
            $('#editDireccion').val(cliente.direccion || '');
            $('#editActivo').prop('checked', cliente.activo);
            $('#editarClienteModal').modal('show');
        }
    });
}

function eliminarCliente(id, nombre) {
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
                url: '{{ url("/api/clientes") }}/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    cargarClientes();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'El cliente ha sido eliminado',
                        confirmButtonColor: '#1a5c1a'
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar el cliente',
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            });
        }
    });
}
</script>
@endsection

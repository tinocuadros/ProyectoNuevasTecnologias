@extends('layouts.app')


@section('contenido')
<style>
    .formulario-permiso .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .tabla-permisos tbody tr {
        line-height: 1;
    }
    .tabla-permisos tbody tr:hover {
        background-color: #f8f9fa;
    }
    .tabla-permisos td {
        padding-top: 4px;
        padding-bottom: 4px;
        font-size: 13px;
    }
    .tabla-permisos th {
        font-size: 13px;
    }
</style>
<div class="container-fluid py-4">
    <div class="row">
        <!-- Izquierda: Formulario de registro -->
        <div class="col-md-4">
            <div class="formulario-permiso">
                <div class="card">
                    <div class="card-header bg-custom text-white">
                        <h5 class="mb-0"><i class="bi bi-shield-plus me-2"></i>Crear Permiso</h5>
                    </div>
                    <div class="card-body">
                        <form id="FormularioRegistroPermiso">
                            @csrf
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Permiso</label>
                                <input type="text" class="form-control" name="nombre" autocomplete="off" placeholder="Ej: Ver Usuarios" required>
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" name="slug" autocomplete="off" placeholder="Ej: ver-usuarios" required>
                            </div>
                            <div class="mb-3">
                                <label for="modulo" class="form-label">Módulo</label>
                                <input type="text" class="form-control" name="modulo" autocomplete="off" placeholder="Ej: Usuarios" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" name="descripcion" rows="3" placeholder="Descripción del permiso"></textarea>
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
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Listado de Permisos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tabla-permisos table-hover">
                            <thead class="bg-custom text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Slug</th>
                                    <th>Módulo</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaPermisosBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Permiso -->
<div class="modal fade" id="editarPermisoModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Permiso</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioEditarPermiso">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label for="editNombre" class="form-label">Nombre del Permiso</label>
                        <input type="text" class="form-control" name="nombre" id="editNombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSlug" class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug" id="editSlug" required>
                    </div>
                    <div class="mb-3">
                        <label for="editModulo" class="form-label">Módulo</label>
                        <input type="text" class="form-control" name="modulo" id="editModulo" required>
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

@endsection


@section('scripts')
<script>
$(document).ready(function() {
    cargarPermisos();

    $('#FormularioRegistroPermiso').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ url("/admin/permisos") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(data) {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Permiso creado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                    $('#FormularioRegistroPermiso')[0].reset();
                    cargarPermisos();
                }
            }
        });
    });

    $('#FormularioEditarPermiso').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editId').val();
        
        $.ajax({
            url: '{{ url("/admin/permisos") }}/' + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(data) {
                if(data.success) {
                    $('#editarPermisoModal').modal('hide');
                    cargarPermisos();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Permiso actualizado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            }
        });
    });

    function cargarPermisos() {
        $.get('{{ url("/admin/permisos") }}', function(data) {
            var html = '';
            data.permisos.forEach(function(permiso) {
                html += '<tr>';
                html += '<td>' + permiso.id + '</td>';
                html += '<td>' + permiso.nombre + '</td>';
                html += '<td>' + permiso.slug + '</td>';
                html += '<td>' + (permiso.modulo || '-') + '</td>';
                html += '<td>' + (permiso.descripcion || '-') + '</td>';
                html += '<td class="text-nowrap text-center">';
                html += '<button class="btn btn-sm btn-primary py-0 px-1 me-1" onclick="editarPermiso(' + permiso.id + ')" title="Editar"><i class="bi bi-pencil" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-danger py-0 px-1" onclick="eliminarPermiso(' + permiso.id + ')" title="Eliminar"><i class="bi bi-trash" style="font-size:12px"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
            $('#tablaPermisosBody').html(html);
        });
    }

    window.editarPermiso = function(id) {
        $.get('{{ url("/admin/permiso") }}/' + id, function(data) {
            $('#editId').val(data.permiso.id);
            $('#editNombre').val(data.permiso.nombre);
            $('#editSlug').val(data.permiso.slug);
            $('#editModulo').val(data.permiso.modulo || '');
            $('#editDescripcion').val(data.permiso.descripcion || '');
            $('#editarPermisoModal').modal('show');
        });
    };

    window.eliminarPermiso = function(id) {
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
                    url: '{{ url("/admin/permisos") }}/' + id,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        cargarPermisos();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'El permiso ha sido eliminado',
                            confirmButtonColor: '#1a5c1a'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar el permiso',
                            confirmButtonColor: '#1a5c1a'
                        });
                    }
                });
            }
        });
    };
});
</script>
@endsection
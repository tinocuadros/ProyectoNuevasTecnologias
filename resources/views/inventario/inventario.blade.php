@extends('layouts.app')

@section('contenido')
<style>
    .tabla-articulos tbody tr {
        line-height: 1;
    }
    .tabla-articulos tbody tr:hover {
        background-color: #f8f9fa;
    }
    .tabla-articulos td {
        padding-top: 4px;
        padding-bottom: 4px;
        font-size: 13px;
    }
    .tabla-articulos th {
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
    .stock-bajo {
        background-color: #fff3cd !important;
    }
    .stock-agotado {
        background-color: #f8d7da !important;
    }
</style>
<div class="container-fluid py-4">
    <!-- Filtros y Crear Artículo -->
    <div class="card mb-3">
        <div class="card-header bg-custom text-white py-1">
            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Catálogo de Artículos</h6>
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
                        <input type="text" class="filtro-input" id="filtroUbicacion" placeholder=" " autocomplete="off">
                        <label class="filtro-label">Ubicación</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="filtro-select-container">
                        <select class="filtro-select" id="filtroStock">
                            <option value="">Stock</option>
                            <option value="bajo">Stock Bajo</option>
                            <option value="normal">Stock Normal</option>
                            <option value="agotado">Agotado</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <span id="loadingIndicator" class="spinner-border spinner-border-sm text-secondary me-2" style="display:none;"></span>
                    <button class="btn bg-custom-btn btn-sm" style="height:36px;padding-top:4px;padding-bottom:4px;" data-bs-toggle="modal" data-bs-target="#crearArticuloModal">
                        <i class="bi bi-plus-circle me-1"></i> Nuevo Artículo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Artículos -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table tabla-articulos table-hover">
                    <thead class="bg-custom text-white">
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Stock</th>
                            <th>Stock Mín.</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaArticulosBody">
                    </tbody>
                </table>
            </div>
            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                <nav>
                    <ul class="pagination" id="paginacionArticulos">
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Artículo -->
<div class="modal fade" id="crearArticuloModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i> Nuevo Artículo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioCrearArticulo">
                    @csrf
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" name="codigo" placeholder="Ej: ART-001">
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label">Precio *</label>
                            <input type="number" step="0.01" class="form-control" name="precio_venta" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stock Inicial *</label>
                            <input type="number" class="form-control" name="stock" required min="0" value="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock_minimo" class="form-label">Stock Mínimo *</label>
                            <input type="number" class="form-control" name="stock_minimo" required min="0" value="5">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" name="ubicacion" placeholder="Ej: Estante A-3">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn bg-custom-btn">Guardar Artículo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Artículo -->
<div class="modal fade" id="editarArticuloModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Editar Artículo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioEditarArticulo">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label for="editCodigo" class="form-label">Código</label>
                        <input type="text" class="form-control" name="codigo" id="editCodigo">
                    </div>
                    <div class="mb-3">
                        <label for="editNombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" name="nombre" id="editNombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="editDescripcion" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editPrecio" class="form-label">Precio *</label>
                            <input type="number" step="0.01" class="form-control" name="precio_venta" id="editPrecio" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editStock" class="form-label">Stock *</label>
                            <input type="number" class="form-control" name="stock" id="editStock" required min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editStockMinimo" class="form-label">Stock Mínimo *</label>
                            <input type="number" class="form-control" name="stock_minimo" id="editStockMinimo" required min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editUbicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" name="ubicacion" id="editUbicacion">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn bg-custom-btn">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var timeoutBusqueda;

$(document).ready(function() {
    cargarArticulos(1);

    $('#filtroCodigo, #filtroNombre, #filtroUbicacion').on('keyup', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            cargarArticulos(1);
        }, 300);
    });

    $('#filtroStock').on('change', function() {
        cargarArticulos(1);
    });

    // Crear Artículo
    $('#FormularioCrearArticulo').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize() + '&_token=' + '{{ csrf_token() }}';
        
        $.ajax({
            url: '{{ url("/api/inventario/articulos") }}',
            method: 'POST',
            data: formData,
            success: function(data) {
                if(data.success) {
                    $('#crearArticuloModal').modal('hide');
                    $('#FormularioCrearArticulo')[0].reset();
                    cargarArticulos();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Artículo creado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            },
            error: function(xhr) {
                var mensaje = 'Error al crear artículo';
                try {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.nombre) mensaje = errors.nombre[0];
                        else if (errors.codigo) mensaje = errors.codigo[0];
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

    // Editar Artículo
    $('#FormularioEditarArticulo').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editId').val();
        var formData = $(this).serialize() + '&_method=PUT&_token=' + '{{ csrf_token() }}';
        
        $.ajax({
            url: '{{ url("/api/inventario/articulos") }}/' + id,
            method: 'POST',
            data: formData,
            success: function(data) {
                if(data.success) {
                    $('#editarArticuloModal').modal('hide');
                    cargarArticulos();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Artículo actualizado correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            },
            error: function(xhr) {
                var mensaje = 'Error al actualizar artículo';
                try {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
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

function cargarArticulos(pagina = 1) {
    var params = new URLSearchParams();
    params.append('page', pagina);
    
    var codigo = $('#filtroCodigo').val();
    var nombre = $('#filtroNombre').val();
    var ubicacion = $('#filtroUbicacion').val();
    var stock = $('#filtroStock').val();
    
    if (codigo) params.append('codigo', codigo);
    if (nombre) params.append('nombre', nombre);
    if (ubicacion) params.append('ubicacion', ubicacion);
    if (stock) params.append('stock', stock);
    
    $('#loadingIndicator').show();
    
    $.get('{{ url("/api/inventario/articulos") }}?' + params.toString(), function(data) {
        $('#loadingIndicator').hide();
        var html = '';
        if (data.data.length === 0) {
            html = '<tr><td colspan="9" class="text-center text-muted">No se encontraron resultados</td></tr>';
        } else {
            data.data.forEach(function(articulo) {
                var rowClass = '';
                if (articulo.stock == 0) {
                    rowClass = 'stock-agotado';
                } else if (articulo.stock <= articulo.stock_minimo) {
                    rowClass = 'stock-bajo';
                }
                
                var estadoTexto = articulo.activo ? 'Activo' : 'Inactivo';
                var estadoBadge = articulo.activo ? 'bg-success' : 'bg-secondary';
                
                html += '<tr class="' + rowClass + '">';
                html += '<td>' + articulo.id + '</td>';
                html += '<td>' + (articulo.codigo || 'N/A') + '</td>';
                html += '<td>' + articulo.nombre + '</td>';
                html += '<td>' + (articulo.ubicacion || 'N/A') + '</td>';
                html += '<td>' + articulo.stock + '</td>';
                html += '<td>' + articulo.stock_minimo + '</td>';
                html += '<td>$' + parseFloat(articulo.precio_venta).toFixed(2) + '</td>';
                html += '<td><span class="badge ' + estadoBadge + '">' + estadoTexto + '</span></td>';
                html += '<td class="text-nowrap text-center">';
                html += '<button class="btn btn-sm btn-primary py-0 px-1 me-1 btn-tooltip" data-tooltip="Editar" onclick="editarArticulo(' + articulo.id + ')"><i class="bi bi-pencil" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-danger py-0 px-1 btn-tooltip" data-tooltip="Eliminar" onclick="eliminarArticulo(' + articulo.id + ', \'' + articulo.nombre.replace(/'/g, "\\'") + '\')"><i class="bi bi-trash" style="font-size:12px"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
        }
        $('#tablaArticulosBody').html(html);

        var paginationHtml = '';
        if (data.prev_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarArticulos(' + (pagina - 1) + ')">Anterior</a></li>';
        }
        for (var i = 1; i <= data.last_page; i++) {
            paginationHtml += '<li class="page-item ' + (i === pagina ? 'active' : '') + '"><a class="page-link" href="#" onclick="cargarArticulos(' + i + ')">' + i + '</a></li>';
        }
        if (data.next_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarArticulos(' + (pagina + 1) + ')">Siguiente</a></li>';
        }
        $('#paginacionArticulos').html(paginationHtml);
    }).fail(function() {
        $('#loadingIndicator').hide();
    });
}

function editarArticulo(id) {
    $.get('{{ url("/api/inventario/articulos") }}/' + id, function(response) {
        if(response.articulo) {
            var art = response.articulo;
            $('#editId').val(art.id);
            $('#editCodigo').val(art.codigo);
            $('#editNombre').val(art.nombre);
            $('#editDescripcion').val(art.descripcion);
            $('#editPrecio').val(art.precio_venta);
            $('#editStock').val(art.stock);
            $('#editStockMinimo').val(art.stock_minimo);
            $('#editUbicacion').val(art.ubicacion);
            $('#editarArticuloModal').modal('show');
        }
    });
}

function eliminarArticulo(id, nombre) {
    Swal.fire({
        title: '¿Eliminar artículo?',
        text: 'Se eliminará: ' + nombre,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("/api/inventario/articulos") }}/' + id,
                method: 'POST',
                data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                success: function(data) {
                    if(data.success) {
                        cargarArticulos();
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: 'Artículo eliminado correctamente',
                            confirmButtonColor: '#1a5c1a'
                        });
                    }
                }
            });
        }
    });
}
</script>
@endsection

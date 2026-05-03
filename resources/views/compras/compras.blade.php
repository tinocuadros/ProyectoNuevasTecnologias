@extends('layouts.app')

@section('contenido')
<style>
    .tabla-compras tbody tr {
        line-height: 1;
    }
    .tabla-compras tbody tr:hover {
        background-color: #f8f9fa;
    }
    .tabla-compras td {
        padding-top: 4px;
        padding-bottom: 4px;
        font-size: 13px;
    }
    .tabla-compras th {
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
    .detalle-row {
        background-color: #f8f9fa;
    }
</style>
<div class="container-fluid py-4">
    <div class="card mb-3">
        <div class="card-header bg-custom text-white py-1">
            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Listado de Compras</h6>
        </div>
        <div class="card-body py-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <div class="filtro-input-container">
                        <input type="text" class="filtro-input" id="filtroFactura" placeholder=" " autocomplete="off">
                        <label class="filtro-label">N° Factura</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filtro-select-container">
                        <select class="filtro-select" id="filtroProveedor">
                            <option value="">Todos los proveedores</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="filtroFechaInicio" placeholder="Desde">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="filtroFechaFin" placeholder="Hasta">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <span id="loadingIndicator" class="spinner-border spinner-border-sm text-secondary me-2" style="display:none;"></span>
                    <button class="btn bg-custom-btn btn-sm w-100" style="height:36px;padding-top:4px;padding-bottom:4px;" data-bs-toggle="modal" data-bs-target="#crearCompraModal">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Compra
                    </button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="table-responsive">
                    <table class="table tabla-compras table-hover">
                        <thead class="bg-custom text-white">
                            <tr>
                                <th>ID</th>
                                <th>N° Factura</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th>Total</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaComprasBody">
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <nav>
                        <ul class="pagination" id="paginacionCompras">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Compra -->
<div class="modal fade" id="crearCompraModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-cart-plus me-2"></i> Nueva Compra</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioCrearCompra">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="numero_factura" class="form-label">N° Factura</label>
                            <input type="text" class="form-control" name="numero_factura" id="numero_factura" placeholder="FAC-001">
                        </div>
                        <div class="col-md-4">
                            <label for="proveedor_id" class="form-label">Proveedor *</label>
                            <select class="form-control" name="proveedor_id" id="proveedor_id" required>
                                <option value="">Seleccione proveedor</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha" class="form-label">Fecha *</label>
                            <input type="date" class="form-control" name="fecha" id="fecha" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="total" class="form-label">Total</label>
                            <input type="text" class="form-control" id="total" readonly value="$0.00">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" rows="2"></textarea>
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Detalles de la Compra</h6>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select class="form-control" id="selectArticulo">
                                <option value="">Seleccione artículo</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="cantidad" placeholder="Cantidad" min="1">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="precio_unitario" placeholder="Precio Unit." step="0.01" min="0">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="subtotal_item" placeholder="Subtotal" readonly>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn bg-custom-btn w-100" onclick="agregarDetalle()">
                                <i class="bi bi-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm" id="tablaDetalles">
                            <thead>
                                <tr>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th>Subtotal</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyDetalles">
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn bg-custom-btn">Guardar Compra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Compra -->
<div class="modal fade" id="detalleCompraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i> Detalle de Compra</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleCompraBody">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var timeoutBusqueda;
var detallesCompra = [];
var articulosCache = [];

$(document).ready(function() {
    cargarCompras();
    cargarProveedores();
    cargarArticulos();

    $('#filtroFactura').on('keyup', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            cargarCompras(1);
        }, 300);
    });

    $('#filtroProveedor, #filtroFechaInicio, #filtroFechaFin').on('change', function() {
        cargarCompras(1);
    });

    $('#cantidad, #precio_unitario').on('input', function() {
        var cantidad = parseFloat($('#cantidad').val()) || 0;
        var precio = parseFloat($('#precio_unitario').val()) || 0;
        var subtotal = cantidad * precio;
        $('#subtotal_item').val('$' + subtotal.toFixed(2));
    });

    $('#FormularioCrearCompra').on('submit', function(e) {
        e.preventDefault();
        
        if (detallesCompra.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe agregar al menos un artículo',
                confirmButtonColor: '#1a5c1a'
            });
            return;
        }
        
        var formData = {
            numero_factura: $('#numero_factura').val(),
            proveedor_id: $('#proveedor_id').val(),
            fecha: $('#fecha').val(),
            observaciones: $('#observaciones').val(),
            detalles: detallesCompra
        };
        
        $.ajax({
            url: '{{ url("/api/compras") }}',
            method: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(data) {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Compra registrada correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                    $('#crearCompraModal').modal('hide');
                    $('#FormularioCrearCompra')[0].reset();
                    detallesCompra = [];
                    $('#tbodyDetalles').empty();
                    $('#total').val('$0.00');
                    cargarCompras();
                }
            },
            error: function(xhr) {
                var mensaje = 'Error al registrar compra';
                try {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        mensaje = Object.values(xhr.responseJSON.errors)[0][0];
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

function cargarCompras(pagina = 1) {
    var params = new URLSearchParams();
    params.append('page', pagina);
    
    var factura = $('#filtroFactura').val();
    var proveedor = $('#filtroProveedor').val();
    var fechaInicio = $('#filtroFechaInicio').val();
    var fechaFin = $('#filtroFechaFin').val();
    
    if (factura) params.append('numero_factura', factura);
    if (proveedor) params.append('proveedor_id', proveedor);
    if (fechaInicio && fechaFin) {
        params.append('fecha_inicio', fechaInicio);
        params.append('fecha_fin', fechaFin);
    }
    
    $('#loadingIndicator').show();
    
    $.get('{{ url("/api/compras") }}?' + params.toString(), function(data) {
        $('#loadingIndicator').hide();
        
        var html = '';
        if (data.data.length === 0) {
            html = '<tr><td colspan="7" class="text-center text-muted">No se encontraron resultados</td></tr>';
        } else {
            data.data.forEach(function(compra) {
                html += '<tr>';
                html += '<td>' + compra.id + '</td>';
                html += '<td>' + (compra.numero_factura || '-') + '</td>';
                html += '<td>' + new Date(compra.fecha).toLocaleDateString() + '</td>';
                html += '<td>' + compra.proveedor.nombre + '</td>';
                html += '<td>$' + parseFloat(compra.total).toFixed(2) + '</td>';
                html += '<td>' + (compra.usuario ? compra.usuario.username : '-') + '</td>';
                html += '<td class="text-nowrap text-center">';
                html += '<button class="btn btn-sm btn-info py-0 px-1 me-1 btn-tooltip" data-tooltip="Ver Detalle" onclick="verDetalle(' + compra.id + ')"><i class="bi bi-eye" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-danger py-0 px-1 btn-tooltip" data-tooltip="Eliminar" onclick="eliminarCompra(' + compra.id + ', \'' + compra.numero_factura + '\')"><i class="bi bi-trash" style="font-size:12px"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
        }
        $('#tablaComprasBody').html(html);

        var paginationHtml = '';
        if (data.prev_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarCompras(' + (pagina - 1) + ')">Anterior</a></li>';
        }
        for (var i = 1; i <= data.last_page; i++) {
            paginationHtml += '<li class="page-item ' + (i === pagina ? 'active' : '') + '"><a class="page-link" href="#" onclick="cargarCompras(' + i + ')">' + i + '</a></li>';
        }
        if (data.next_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarCompras(' + (pagina + 1) + ')">Siguiente</a></li>';
        }
        $('#paginacionCompras').html(paginationHtml);
    }).fail(function() {
        $('#loadingIndicator').hide();
    });
}

function cargarProveedores() {
    $.get('{{ url("/api/proveedores") }}', function(data) {
        var html = '<option value="">Todos los proveedores</option>';
        data.data.forEach(function(proveedor) {
            html += '<option value="' + proveedor.id + '">' + proveedor.nombre + '</option>';
        });
        $('#filtroProveedor').html(html);
        $('#proveedor_id').html('<option value="">Seleccione proveedor</option>' + html);
    });
}

function cargarArticulos() {
    $.get('{{ url("/api/inventario/articulos") }}', function(data) {
        var html = '<option value="">Seleccione artículo</option>';
        articulosCache = data.data;
        data.data.forEach(function(articulo) {
            html += '<option value="' + articulo.id + '" data-precio="' + articulo.precio + '">' + articulo.nombre + ' (Stock: ' + articulo.stock + ')</option>';
        });
        $('#selectArticulo').html(html);
    });
}

function agregarDetalle() {
    var articuloId = $('#selectArticulo').val();
    var cantidad = parseInt($('#cantidad').val());
    var precioUnitario = parseFloat($('#precio_unitario').val());
    
    if (!articuloId || !cantidad || cantidad < 1 || !precioUnitario || precioUnitario < 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Complete los datos del artículo',
            confirmButtonColor: '#1a5c1a'
        });
        return;
    }
    
    var articulo = articulosCache.find(function(a) { return a.id == articuloId; });
    var subtotal = cantidad * precioUnitario;
    
    detallesCompra.push({
        articulo_id: articuloId,
        cantidad: cantidad,
        precio_unitario: precioUnitario,
        subtotal: subtotal
    });
    
    actualizarTablaDetalles();
    
    $('#selectArticulo').val('');
    $('#cantidad').val('');
    $('#precio_unitario').val('');
    $('#subtotal_item').val('');
}

function actualizarTablaDetalles() {
    var html = '';
    var total = 0;
    
    detallesCompra.forEach(function(detalle, index) {
        var articulo = articulosCache.find(function(a) { return a.id == detalle.articulo_id; });
        html += '<tr>';
        html += '<td>' + (articulo ? articulo.nombre : 'Artículo no encontrado') + '</td>';
        html += '<td>' + detalle.cantidad + '</td>';
        html += '<td>$' + parseFloat(detalle.precio_unitario).toFixed(2) + '</td>';
        html += '<td>$' + parseFloat(detalle.subtotal).toFixed(2) + '</td>';
        html += '<td><button type="button" class="btn btn-sm btn-danger" onclick="eliminarDetalle(' + index + ')"><i class="bi bi-trash"></i></button></td>';
        html += '</tr>';
        total += detalle.subtotal;
    });
    
    $('#tbodyDetalles').html(html);
    $('#total').val('$' + total.toFixed(2));
}

function eliminarDetalle(index) {
    detallesCompra.splice(index, 1);
    actualizarTablaDetalles();
}

function verDetalle(id) {
    $.get('{{ url("/api/compras") }}/' + id, function(data) {
        if (data.compra) {
            var compra = data.compra;
            var html = '<div class="row">';
            html += '<div class="col-md-6 mb-3"><strong>N° Factura:</strong><br>' + (compra.numero_factura || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Fecha:</strong><br>' + new Date(compra.fecha).toLocaleDateString() + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Proveedor:</strong><br>' + compra.proveedor.nombre + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Total:</strong><br>$' + parseFloat(compra.total).toFixed(2) + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Usuario:</strong><br>' + (compra.usuario ? compra.usuario.username : '-') + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Observaciones:</strong><br>' + (compra.observaciones || 'Ninguna') + '</div>';
            html += '</div>';
            
            if (compra.detalles && compra.detalles.length > 0) {
                html += '<hr><h6>Artículos Comprados</h6>';
                html += '<table class="table table-sm">';
                html += '<thead><tr><th>Artículo</th><th>Cantidad</th><th>Precio Unit.</th><th>Subtotal</th></tr></thead>';
                html += '<tbody>';
                compra.detalles.forEach(function(detalle) {
                    html += '<tr>';
                    html += '<td>' + (detalle.articulo ? detalle.articulo.nombre : 'Artículo no encontrado') + '</td>';
                    html += '<td>' + detalle.cantidad + '</td>';
                    html += '<td>$' + parseFloat(detalle.precio_unitario).toFixed(2) + '</td>';
                    html += '<td>$' + parseFloat(detalle.subtotal).toFixed(2) + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
            }
            
            $('#detalleCompraBody').html(html);
            $('#detalleCompraModal').modal('show');
        }
    });
}

function eliminarCompra(id, numero) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar la compra " + (numero || '#' + id) + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("/api/compras") }}/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    cargarCompras();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'La compra ha sido eliminada y el stock revertido',
                        confirmButtonColor: '#1a5c1a'
                    });
                },
                error: function(xhr) {
                    var mensaje = 'No se pudo eliminar la compra';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: mensaje,
                        confirmButtonColor: '#1a5c1a'
                    });
                }
            });
        }
    });
}
</script>
@endsection

<?php $__env->startSection('contenido'); ?>
<style>
    .tabla-ventas tbody tr {
        line-height: 1;
    }
    .tabla-ventas tbody tr:hover {
        background-color: #f8f9fa;
    }
    .tabla-ventas td {
        padding-top: 4px;
        padding-bottom: 4px;
        font-size: 13px;
    }
    .tabla-ventas th {
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
            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Listado de Ventas</h6>
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
                        <select class="filtro-select" id="filtroCliente">
                            <option value="">Todos los clientes</option>
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
                    <button class="btn bg-custom-btn btn-sm w-100" style="height:36px;padding-top:4px;padding-bottom:4px;" data-bs-toggle="modal" data-bs-target="#crearVentaModal">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Venta
                    </button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="table-responsive">
                    <table class="table tabla-ventas table-hover">
                        <thead class="bg-custom text-white">
                            <tr>
                                <th>ID</th>
                                <th>N° Factura</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaVentasBody">
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <nav>
                        <ul class="pagination" id="paginacionVentas">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Venta -->
<div class="modal fade" id="crearVentaModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-cart-dash me-2"></i> Nueva Venta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="FormularioCrearVenta">
                    <?php echo csrf_field(); ?>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="cliente_id" class="form-label">Cliente *</label>
                            <select class="form-control" name="cliente_id" id="cliente_id" required>
                                <option value="">Seleccione cliente</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha_venta" class="form-label">Fecha *</label>
                            <input type="date" class="form-control" name="fecha_venta" id="fecha_venta" required value="<?php echo e(date('Y-m-d')); ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="total" class="form-label">Total</label>
                            <input type="text" class="form-control" id="total" readonly value="$0.00">
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Detalles de la Venta</h6>
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
                        <button type="submit" class="btn bg-custom-btn">Guardar Venta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Venta -->
<div class="modal fade" id="detalleVentaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-custom text-white">
                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i> Detalle de Venta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleVentaBody">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
var timeoutBusqueda;
var detallesVenta = [];
var articulosCache = [];

$(document).ready(function() {
    cargarVentas();
    cargarClientes();
    cargarArticulos();

    $('#filtroFactura').on('keyup', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(function() {
            cargarVentas(1);
        }, 300);
    });

    $('#filtroCliente, #filtroFechaInicio, #filtroFechaFin').on('change', function() {
        cargarVentas(1);
    });

    $('#cantidad, #precio_unitario').on('input', function() {
        var cantidad = parseFloat($('#cantidad').val()) || 0;
        var precio = parseFloat($('#precio_unitario').val()) || 0;
        var subtotal = cantidad * precio;
        $('#subtotal_item').val('$' + subtotal.toFixed(2));
    });

    $('#FormularioCrearVenta').on('submit', function(e) {
        e.preventDefault();
        
        if (detallesVenta.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe agregar al menos un artículo',
                confirmButtonColor: '#1a5c1a'
            });
            return;
        }
        
        var formData = {
            cliente_id: $('#cliente_id').val(),
            fecha_venta: $('#fecha_venta').val(),
            detalles: detallesVenta
        };
        
        $.ajax({
            url: '<?php echo e(url("/api/ventas")); ?>',
            method: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            success: function(data) {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Venta registrada correctamente',
                        confirmButtonColor: '#1a5c1a'
                    });
                    $('#crearVentaModal').modal('hide');
                    $('#FormularioCrearVenta')[0].reset();
                    detallesVenta = [];
                    $('#tbodyDetalles').empty();
                    $('#total').val('$0.00');
                    cargarVentas();
                }
            },
            error: function(xhr) {
                var mensaje = 'Error al registrar venta';
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

function cargarVentas(pagina = 1) {
    var params = new URLSearchParams();
    params.append('page', pagina);
    
    var factura = $('#filtroFactura').val();
    var cliente = $('#filtroCliente').val();
    var fechaInicio = $('#filtroFechaInicio').val();
    var fechaFin = $('#filtroFechaFin').val();
    
    if (factura) params.append('numero_factura', factura);
    if (cliente) params.append('cliente_id', cliente);
    if (fechaInicio && fechaFin) {
        params.append('fecha_inicio', fechaInicio);
        params.append('fecha_fin', fechaFin);
    }
    
    $('#loadingIndicator').show();
    
    $.get('<?php echo e(url("/api/ventas")); ?>?' + params.toString(), function(data) {
        $('#loadingIndicator').hide();
        
        var html = '';
        if (data.data.length === 0) {
            html = '<tr><td colspan="7" class="text-center text-muted">No se encontraron resultados</td></tr>';
        } else {
            data.data.forEach(function(venta) {
                html += '<tr>';
                html += '<td>' + venta.id + '</td>';
                html += '<td>' + (venta.numero_factura || '-') + '</td>';
                html += '<td>' + new Date(venta.fecha_venta).toLocaleDateString() + '</td>';
                html += '<td>' + (venta.cliente ? venta.cliente.nombre : '-') + '</td>';
                html += '<td>$' + parseFloat(venta.total).toFixed(2) + '</td>';
                html += '<td>' + (venta.usuario ? venta.usuario.username : '-') + '</td>';
                html += '<td class="text-nowrap text-center">';
                html += '<button class="btn btn-sm btn-info py-0 px-1 me-1 btn-tooltip" data-tooltip="Ver Detalle" onclick="verDetalle(' + venta.id + ')"><i class="bi bi-eye" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-success py-0 px-1 me-1 btn-tooltip" data-tooltip="Generar Factura" onclick="window.open(\'<?php echo e(url("/ventas/ver-factura")); ?>/' + venta.id + '\', \'_blank\')"><i class="bi bi-file-earmark-pdf" style="font-size:12px"></i></button>';
                html += '<button class="btn btn-sm btn-danger py-0 px-1 btn-tooltip" data-tooltip="Eliminar" onclick="eliminarVenta(' + venta.id + ', \'' + venta.numero_factura + '\')"><i class="bi bi-trash" style="font-size:12px"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
        }
        $('#tablaVentasBody').html(html);

        var paginationHtml = '';
        if (data.prev_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarVentas(' + (pagina - 1) + ')">Anterior</a></li>';
        }
        for (var i = 1; i <= data.last_page; i++) {
            paginationHtml += '<li class="page-item ' + (i === pagina ? 'active' : '') + '"><a class="page-link" href="#" onclick="cargarVentas(' + i + ')">' + i + '</a></li>';
        }
        if (data.next_page_url) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="cargarVentas(' + (pagina + 1) + ')">Siguiente</a></li>';
        }
        $('#paginacionVentas').html(paginationHtml);
    }).fail(function() {
        $('#loadingIndicator').hide();
    });
}

function cargarClientes() {
    $.get('<?php echo e(url("/api/clientes")); ?>', function(data) {
        var html = '<option value="">Todos los clientes</option>';
        data.data.forEach(function(cliente) {
            html += '<option value="' + cliente.id + '">' + cliente.nombre + '</option>';
        });
        $('#filtroCliente').html(html);
        $('#cliente_id').html('<option value="">Seleccione cliente</option>' + html);
    });
}

function cargarArticulos() {
    $.get('<?php echo e(url("/api/inventario/articulos")); ?>', function(data) {
        var html = '<option value="">Seleccione artículo</option>';
        articulosCache = data.data;
        data.data.forEach(function(articulo) {
            html += '<option value="' + articulo.id + '" data-precio="' + articulo.precio_venta + '">' + articulo.nombre + ' (Stock: ' + articulo.stock + ')</option>';
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
    
    // Verificar stock
    if (articulo.stock < cantidad) {
        Swal.fire({
            icon: 'warning',
            title: 'Stock insuficiente',
            text: 'Solo hay ' + articulo.stock + ' unidades disponibles de ' + articulo.nombre,
            confirmButtonColor: '#1a5c1a'
        });
        return;
    }
    
    var subtotal = cantidad * precioUnitario;
    
    detallesVenta.push({
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
    
    detallesVenta.forEach(function(detalle, index) {
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
    detallesVenta.splice(index, 1);
    actualizarTablaDetalles();
}

function verDetalle(id) {
    $.get('<?php echo e(url("/api/ventas")); ?>/' + id, function(data) {
        if (data.venta) {
            var venta = data.venta;
            var html = '<div class="row">';
            html += '<div class="col-md-6 mb-3"><strong>N° Factura:</strong><br>' + (venta.numero_factura || 'No registra') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Fecha:</strong><br>' + new Date(venta.fecha_venta).toLocaleDateString() + '</div>';
            html += '<div class="col-md-12 mb-3"><strong>Cliente:</strong><br>' + (venta.cliente ? venta.cliente.nombre : '-') + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Total:</strong><br>$' + parseFloat(venta.total).toFixed(2) + '</div>';
            html += '<div class="col-md-6 mb-3"><strong>Usuario:</strong><br>' + (venta.usuario ? venta.usuario.username : '-') + '</div>';
            html += '</div>';
            
            if (venta.detalles && venta.detalles.length > 0) {
                html += '<hr><h6>Artículos Vendidos</h6>';
                html += '<table class="table table-sm">';
                html += '<thead><tr><th>Artículo</th><th>Cantidad</th><th>Precio Unit.</th><th>Subtotal</th></tr></thead>';
                html += '<tbody>';
                venta.detalles.forEach(function(detalle) {
                    html += '<tr>';
                    html += '<td>' + (detalle.articulo ? detalle.articulo.nombre : 'Artículo no encontrado') + '</td>';
                    html += '<td>' + detalle.cantidad + '</td>';
                    html += '<td>$' + parseFloat(detalle.precio_unitario).toFixed(2) + '</td>';
                    html += '<td>$' + parseFloat(detalle.subtotal).toFixed(2) + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
            }
            
            $('#detalleVentaBody').html(html);
            $('#detalleVentaModal').modal('show');
        }
    });
}

// Función removida - ahora se usa window.open directo al visor

function eliminarVenta(id, numero) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar la venta " + (numero || '#' + id) + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo e(url("/api/ventas")); ?>/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                success: function(data) {
                    cargarVentas();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'La venta ha sido eliminada y el stock revertido',
                        confirmButtonColor: '#1a5c1a'
                    });
                },
                error: function(xhr) {
                    var mensaje = 'No se pudo eliminar la venta';
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\inventario\resources\views/ventas/ventas.blade.php ENDPATH**/ ?>
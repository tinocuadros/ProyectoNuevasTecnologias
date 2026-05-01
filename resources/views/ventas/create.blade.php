@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Registrar Nueva Venta</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Cliente</label>
                <select name="id_cliente" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    @foreach($clientes as $c)
                        <option value="{{ $c->id_cliente }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Vendedor</label>
                <select name="id_usuario" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id_usuario }}">{{ $u->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Método de Pago</label>
                <select name="metodo_pago" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
            </div>
        </div>

        <h5>Productos</h5>
        <table class="table table-bordered" id="tablaProductos">
            <thead class="table-secondary">
                <tr>
                    <th>Producto</th>
                    <th>Stock Disponible</th>
                    <th>Precio Unit.</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="filas">
                <tr class="fila">
                    <td>
                        <select name="productos[0][id_producto]" class="form-select producto-select" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id_producto }}" data-precio="{{ $p->precio }}" data-stock="{{ $p->stock }}">
                                    {{ $p->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" class="form-control stock-disponible" readonly value="-"></td>
                    <td><input type="number" name="productos[0][precio_unitario]" class="form-control precio" step="0.01" readonly></td>
                    <td><input type="number" name="productos[0][cantidad]" class="form-control cantidad" min="1" value="1" required></td>
                    <td><input type="text" class="form-control subtotal" readonly value="0.00"></td>
                    <td><button type="button" class="btn btn-danger btn-sm eliminar">X</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-secondary mb-3" id="agregarFila">+ Agregar Producto</button>

        <div class="text-end mb-3">
            <h5>Total: $<span id="totalGeneral">0.00</span></h5>
        </div>

        <button type="submit" class="btn btn-success">Guardar Venta</button>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
let filaIndex = 1;
const productosData = @json($productos->keyBy('id_producto'));

function calcularFila(fila) {
    const precio = parseFloat(fila.querySelector('.precio').value) || 0;
    const cantidad = parseInt(fila.querySelector('.cantidad').value) || 0;
    fila.querySelector('.subtotal').value = (precio * cantidad).toFixed(2);
    calcularTotal();
}

function calcularTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(s => total += parseFloat(s.value) || 0);
    document.getElementById('totalGeneral').textContent = total.toFixed(2);
}

document.getElementById('filas').addEventListener('change', function(e) {
    const fila = e.target.closest('.fila');

    if (e.target.classList.contains('producto-select')) {
        const id = e.target.value;
        if (id && productosData[id]) {
            const stock = productosData[id].stock;
            const precio = productosData[id].precio;

            fila.querySelector('.precio').value = precio;
            fila.querySelector('.stock-disponible').value = stock + ' unidades';

            // ✅ Limita la cantidad máxima al stock disponible
            const inputCantidad = fila.querySelector('.cantidad');
            inputCantidad.max = stock;
            inputCantidad.value = 1;

            // ✅ Color rojo si stock es bajo
            if (stock <= 5) {
                fila.querySelector('.stock-disponible').classList.add('text-danger', 'fw-bold');
            } else {
                fila.querySelector('.stock-disponible').classList.remove('text-danger', 'fw-bold');
            }
        } else {
            fila.querySelector('.precio').value = 0;
            fila.querySelector('.stock-disponible').value = '-';
            fila.querySelector('.cantidad').max = '';
        }
        calcularFila(fila);
    }

    if (e.target.classList.contains('cantidad')) {
        const id = fila.querySelector('.producto-select').value;
        const stockMax = id && productosData[id] ? parseInt(productosData[id].stock) : 0;
        const cantidad = parseInt(e.target.value) || 0;

        // ✅ Bloquea si supera el stock
        if (cantidad > stockMax) {
            e.target.value = stockMax;
            alert(`⚠️ Stock máximo disponible: ${stockMax} unidades`);
        }
        calcularFila(fila);
    }
});

document.getElementById('agregarFila').addEventListener('click', function() {
    const template = document.querySelector('.fila').cloneNode(true);
    template.querySelectorAll('input').forEach(i => {
        if (i.classList.contains('stock-disponible')) {
            i.value = '-';
        } else if (i.type === 'number') {
            i.value = '1';
        } else {
            i.value = '';
        }
    });
    template.querySelector('.subtotal').value = '0.00';
    template.querySelector('.producto-select').value = '';
    template.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, `[${filaIndex}]`);
    });
    document.getElementById('filas').appendChild(template);
    filaIndex++;
});

document.getElementById('filas').addEventListener('click', function(e) {
    if (e.target.classList.contains('eliminar')) {
        if (document.querySelectorAll('.fila').length > 1) {
            e.target.closest('.fila').remove();
            calcularTotal();
        }
    }
});
</script>
@endsection
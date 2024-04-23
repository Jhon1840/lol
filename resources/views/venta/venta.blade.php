<?php
use App\Models\Product;
$products = Product::pluck('Nombre', 'id')->all();
$precios = Product::pluck('Precio_venta', 'id')->all();
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Vista para la selección de productos y carrito -->
<div class="container">
    <!-- Seleccion de producto -->
    <div class="form-group mb-3">
        <label class="form-label">Producto</label>
        <select id="producto" class="form-control">
            <option value="" disabled selected>Seleccione un producto</option>
            @foreach ($products as $id => $nombre)
                <option value="{{ $id }}" data-precio="{{ $precios[$id] }}">{{ $nombre }} - Precio:
                    {{ $precios[$id] }}</option>
            @endforeach
        </select>
    </div>

    <!-- Cantidad del producto -->
    <div class="form-group mb-3">
        <label class="form-label">Cantidad</label>
        <input type="number" id="cantidad" class="form-control" placeholder="Cantidad">
    </div>

    <!-- Botón para añadir al carrito -->
    <button id="add-to-cart" class="btn btn-primary">Añadir al carrito</button>

    <!-- Carrito de compras -->
    <h3>Carrito</h3>
    <ul id="carrito"></ul>
    <strong>Total: $<span id="total">0.00</span></strong>
</div>

<script>
    let carrito = [];

    $(document).on('click', '#add-to-cart', function() {
        const productoId = $('#producto').val();
        const precio = $('#producto option:selected').data('precio');
        const cantidad = parseInt($('#cantidad').val());
        const nombreProducto = $('#producto option:selected').text();

        // Verificar si el producto ya está en el carrito
        const index = carrito.findIndex(item => item.id === productoId);
        if (index > -1) {
            carrito[index].cantidad += cantidad;
        } else {
            carrito.push({
                id: productoId,
                nombre: nombreProducto,
                cantidad,
                precio
            });
        }

        actualizarCarrito();
    });

    function actualizarCarrito() {
        let total = 0;
        $('#carrito').empty();

        carrito.forEach(item => {
            total += item.cantidad * item.precio;
            $('#carrito').append(
                `<li>${item.nombre} - Cantidad: ${item.cantidad} - Subtotal: $${(item.cantidad * item.precio).toFixed(2)}</li>`
                );
        });

        $('#total').text(total.toFixed(2));
    }
</script>

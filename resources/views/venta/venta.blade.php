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

@extends('tablar::page')

@section('title', 'Create Venta')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">Crear</div>
                    <h2 class="page-title">{{ __('Venta ') }}</h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('ventas.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Icon from Tabler Icons -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Ventas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if (config('tablar', 'display_alert'))
                @include('tablar::common.alert')
            @endif
            <div class="row row-deck row-cards">
                <!-- Product Cards -->
                @foreach ($products as $id => $nombre)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-sm">
                            <a href="#" class="d-block"><img src="" class="card-img-top"></a>
                            <div class="card-body">
                                <h5 class="card-title">{{ $nombre }}</h5>
                                <p>Precio: ${{ $precios[$id] }}</p>
                                <div class="d-flex">
                                    <input type="number" id="cantidad-{{ $id }}" class="form-control text-end"
                                        value="1" min="1">
                                    <button data-product-id="{{ $id }}" data-product-name="{{ $nombre }}"
                                        data-product-price="{{ $precios[$id] }}"
                                        class="btn btn-primary ms-2 add-to-cart">Añadir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-12">
                    <h4>Carrito de Compras</h4>
                    <ul id="carrito"></ul>
                    <strong>Total: $<span id="total">0.00</span></strong>
                </div>
            </div>
        </div>
    </div>
@endsection

@yield('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let carrito = [];

    function addToCart(productId, productName, price) {
        const cantidad = parseInt($('#cantidad-' + productId).val());
        const index = carrito.findIndex(item => item.id === productId);
        if (index > -1) {
            carrito[index].cantidad += cantidad;
            console.log(
                `Actualizado en el carrito: ${productName}, Cantidad: ${carrito[index].cantidad}, Precio: ${price}`);
        } else {
            carrito.push({
                id: productId,
                nombre: productName,
                cantidad,
                precio: price
            });
            console.log(`Añadido al carrito: ${productName}, Cantidad: ${cantidad}, Precio: ${price}`);
        }

        actualizarCarrito();
    }

    function actualizarCarrito() {
        let total = 0;
        $('#carrito').empty();
        console.log("Carrito actualizado:");
        carrito.forEach(item => {
            total += item.cantidad * item.precio;
            $('#carrito').append(
                `<li>${item.nombre} - Cantidad: ${item.cantidad} - Subtotal: $${(item.cantidad * item.precio).toFixed(2)}</li>`
            );
            console.log(
                `${item.nombre} - Cantidad: ${item.cantidad} - Subtotal: $${(item.cantidad * item.precio).toFixed(2)}`
            );
        });
        $('#total').text(total.toFixed(2));
        console.log(`Total del Carrito: $${total.toFixed(2)}`);
    }

    $(document).ready(function() {
        $('.add-to-cart').click(function() {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const price = $(this).data('product-price');
            addToCart(productId, productName, price);
        });
    });
</script>
@yield('scripts')

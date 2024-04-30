@extends('tablar::page')

@section('title', 'Create Venta')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

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
            <div class="row">
                <div class="col-md-8">
                    <!-- Productos -->
                    <div class="row row-cards">
                        <!-- Product Cards -->
                        @foreach ($products as $id => $nombre)
                            <div class="col-sm-6 col-lg-4">
                                <div class="card card-sm clickable-card bg-dark text-white"
                                    data-product-id="{{ $id }}" data-product-name="{{ $nombre }}"
                                    data-product-price="{{ $precios[$id] }}" style="cursor: pointer;">
                                    <!-- Aquí se añade la imagen -->
                                    <a href="#" class="d-block"><img
                                            src="https://png.pngtree.com/png-vector/20220519/ourlarge/pngtree-premium-white-icon-with-crown-on-black-background-vector-png-image_46216252.jpg"
                                            class="card-img-top"></a>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $nombre }}</h5>
                                        <p>Precio: ${{ $precios[$id] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach



                    </div>
                </div>
                <div class="col-md-4">
                    <!-- [Código HTML para alertas y otros elementos de la página omitido para brevedad] -->
                    <!-- Carrito de compras -->
                    <div class="shopping-cart card">
                        <div class="card-header">
                            <h3 class="card-title">Lista de compras</h3>
                        </div>
                        <div class="card-body">
                            <!-- Productos en el carrito -->
                            <ul class="list-group mb-3" id="carrito">
                                <!-- Los productos se agregarán aquí -->
                            </ul>

                            <!-- Subtotal y Totales -->
                            <div class="d-flex justify-content-between mb-3">
                                <span>Subtotal</span>
                                <span class="fw-bold" id="subtotal">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>IVA (13%)</span>
                                <span class="fw-bold" id="iva">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Total</span>
                                <span class="fw-bold" id="total">$0.00</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- Acciones -->
                            <div class="d-grid gap-2">
                                <button class="btn btn-warning">Cancelar Order</button>
                                <button class="btn btn-success btn-proceed" id="btnProceedPago" data-bs-toggle="modal"
                                    data-bs-target="#modalPago">Proceder al pago</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Proceder al Pago -->
    <div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPagoLabel">Proceder al Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('ventas.store') }}" id="ajaxForm" role="form"
                        enctype="multipart/form-data">
                        @csrf
                        @include('venta.form')
                        <div id="productosForm"></div>
                    </form>

                </div>
            </div>
        </div>
    </div>



    <script>
        let carrito = [];

        function addToCart(productId, productName, price) {
            const index = carrito.findIndex(item => item.id === productId);
            if (index > -1) {
                // Incrementa la cantidad del producto en el carrito
                carrito[index].cantidad += 1;
            } else {
                // Añade el producto al carrito con una cantidad inicial de 1
                carrito.push({
                    id: productId,
                    nombre: productName,
                    cantidad: 1,
                    precio: price
                });
            }

            actualizarCarrito();
        }

        function actualizarCarrito() {
            let subtotal = 0;
            $('#carrito').empty();
            $('#productosForm').empty();

            carrito.forEach(item => {
                const subtotalItem = item.cantidad * item.precio;
                subtotal += subtotalItem;

                $('#carrito').append(
                    `<li>${item.nombre} - Cantidad: ${item.cantidad} - Subtotal: $${subtotalItem.toFixed(2)}</li>`
                );

                $('#productosForm').append(`
                    <input type="hidden" name="productos[${item.id}][id]" value="${item.id}">
                    <input type="hidden" name="productos[${item.id}][cantidad]" value="${item.cantidad}">
                `);
            });

            const iva = subtotal * 0.13;
            const total = subtotal + iva;

            $('#subtotal').text(`$${subtotal.toFixed(2)}`);
            $('#iva').text(`$${iva.toFixed(2)}`);
            $('#total').text(`$${total.toFixed(2)}`);
            $('#inputTotalCarrito').val(total.toFixed(2));
        }

        $(document).ready(function() {
            $('.clickable-card').click(function(event) {
                event.preventDefault();
                const productId = $(this).data('product-id');
                const productName = $(this).data('product-name');
                const price = parseFloat($(this).data('product-price'));
                addToCart(productId, productName, price);
            });
        });
    </script>
@endsection

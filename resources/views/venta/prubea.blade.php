@php
    use App\Models\Caja;
    use App\Models\Product;
    use App\Models\Descuento;
    use Illuminate\Support\Facades\Auth;
    $usuarioLogueado = Auth::user();
    $cajaAbierta = Caja::where('nombre_vendedor', $usuarioLogueado->name)
        ->where('estado', 'caja abierta')
        ->latest()
        ->first();
    $dineroCajaAbierta = $cajaAbierta ? $cajaAbierta->dinero : 0;
    // Obtener los productos y precios originales
    $products = Product::pluck('Nombre', 'id');
    $preciosOriginales = Product::pluck('Precio_venta', 'id');
    // Obtener los descuentos aplicables
    $descuentos = Descuento::where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->get()
        ->keyBy('product_id');

    // Calcular los precios con descuento
    $precios = [];
    foreach ($preciosOriginales as $id => $precioOriginal) {
        if (isset($descuentos[$id])) {
            $descuento = $descuentos[$id];
            $precioConDescuento = $precioOriginal - $precioOriginal * ($descuento->discount_percentage / 100);
            $precios[$id] = $precioConDescuento;
        } else {
            $precios[$id] = $precioOriginal;
        }
    }

@endphp

@extends('tablar::page')

@section('title', 'Create Venta')

@section('content')
    @include('venta.modals.modal_confirmar_abrir_caja')
    @include('venta.modals.modal_confirmar_cerrar_caja')
    @include('venta.modals.modal_abrir_caja_primero')
    @include('venta.modals.modal_pago')
    @include('venta.modals.modal_cancelar_pago')
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
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
                    <button id="toggleCajaBtn" class="btn {{ $cajaAbierta ? 'btn-warning' : 'btn-primary' }}"
                        data-action="{{ $cajaAbierta ? 'cerrar' : 'abrir' }}" onclick="toggleCaja()">
                        {{ $cajaAbierta ? 'Cerrar Caja' : 'Abrir Caja' }}
                    </button>
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
                                    data-product-price="{{ $precios[$id] }}" style="cursor: pointer; position: relative;">
                                    <div class="image-container">
                                        <a href="#" class="d-block">
                                            <img src="https://png.pngtree.com/png-vector/20220519/ourlarge/pngtree-premium-white-icon-with-crown-on-black-background-vector-png-image_46216252.jpg"
                                                class="card-img-top product-image">
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $nombre }}</h5>
                                        @if ($precios[$id] < $preciosOriginales[$id])
                                            <p class="text-danger">
                                                <del>Precio: ${{ $preciosOriginales[$id] }}</del>
                                            </p>
                                            <p class="text-success">Descuento aplicado:
                                                ${{ number_format($preciosOriginales[$id] - $precios[$id], 2) }}
                                            </p>
                                            <p>Precio con descuento: ${{ $precios[$id] }}</p>
                                        @else
                                            <p>Precio: ${{ $precios[$id] }}</p>
                                        @endif
                                    </div>
                                    @if ($precios[$id] < $preciosOriginales[$id])
                                        <div class="discount-label bg-danger text-white">
                                            Descuento
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
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
                            <button class="btn btn-success btn-proceed" id="btnProceedPago" data-bs-toggle="modal"
                                data-bs-target="#modalPago">Proceder al pago</button>
                            <button class="btn btn-warning" id="btnCancelarVenta" data-bs-toggle="modal"
                                data-bs-target="#modalCancelar">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .discount-label {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
        }

        .image-container {
            position: relative;
            width: 100%;
            padding-bottom: 100%;
            /* Mantener la proporción cuadrada */
            overflow: hidden;
        }

        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card .card-body h5 {
            flex-grow: 1;
        }

        .card .card-body p {
            margin-bottom: 0;
        }
    </style>

    <script>
        var cajaAbierta = @json($cajaAbierta);
    </script>

    <script src="{{ asset('js/caja.js') }}"></script>

    <!-- Modal de Error -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="errorModalBody">
                    <!-- Aquí se mostrará el contenido HTML del error -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

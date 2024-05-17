@php
    use App\Models\Caja;
    use Illuminate\Support\Facades\Auth;

    $usuarioLogueado = Auth::user();

    $cajaAbierta = Caja::where('nombre_vendedor', $usuarioLogueado->name)
        ->where('estado', 'caja abierta')
        ->latest()
        ->first();

    //$datos_caja = Caja::where('nombre_vendedor', $usuarioLogueado->name)
    //dd($cajaAbierta, $usuarioLogueado->name);

@endphp



@extends('tablar::page')

@section('title', 'Create Venta')

@section('content')
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
        data-action="{{ $cajaAbierta ? 'cerrar' : 'abrir' }}"
        onclick="toggleCaja()"> 
    {{ $cajaAbierta ? 'Cerrar Caja' : 'Abrir Caja' }}
</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Cerrar Caja -->
    <div class="modal fade" id="modalConfirmacionCerrarCaja" tabindex="-1"
        aria-labelledby="modalConfirmacionCerrarCajaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmacionCerrarCajaLabel">Cerrar Caja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea cerrar la caja?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="cerrarCaja()">Sí, Cerrar Caja</button>
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
                        <!-- Agrega un campo oculto para el estado de la venta -->
                        <input type="hidden" name="estado" value="Cancelada">
                        <!-- Agrega un campo oculto para el identificador único de la orden de venta -->
                        <input type="hidden" name="order_id" value="{{ uniqid() }}">
                        @include('venta.form')
                        <div id="productosForm"></div>
                        <input type="hidden" name="caja_id" value="" id="inputCajaId">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCancelar" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPagoLabel">Cancelar Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('cancelar-venta') }}" id="pagoForm">
                        @csrf
                        <input type="hidden" name="estado" value="Cancelado">
                        <input type="hidden" name="order_id" value="{{ uniqid() }}">
                        <!-- Enviar fecha del servidor -->
                        <input type="hidden" name="fecha_servidor" value="{{ now()->toDateTimeString() }}">
                        <div id="productosForm"></div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-primary">Sí</button>
                        </div>
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
                    <input type="hidden" name="productos[${item.id}][precio]" value="${item.precio}">
                    <input type="hidden" name="productos[${item.id}][subtotal]" value="${subtotalItem}">
                `);
            });

            const iva = subtotal * 0.13;
            const total = subtotal + iva;

            // Redondeo del total usando Math.ceil
            const totalRedondeado = Math.ceil(total);

            $('#subtotal').text(`$${subtotal.toFixed(2)}`);
            $('#iva').text(`$${iva.toFixed(2)}`);
            $('#total').text(`$${totalRedondeado}`);
            $('#inputTotalCarrito').val(totalRedondeado);
        }

        function abrirCaja() {
            return fetch('/ventas/toggleCaja', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        abrir: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#inputCajaId').val(data.cajaId);
                        $('#botonCaja').text('Cerrar Caja').removeClass('btn-primary').addClass('btn-warning');
                        console.log('Caja abierta con ID:', data.cajaId);
                    } else {
                        // Si no se pudo abrir una nueva caja pero se recibió un ID de caja ya abierta
                        if (data.cajaId) {
                            $('#inputCajaId').val(data.cajaId);
                            $('#botonCaja').text('Cerrar Caja').removeClass('btn-primary').addClass('btn-warning');
                            console.warn('Usando caja existente con ID:', data.cajaId);
                        }
                        console.error('Error al abrir la caja:', data.message);
                        throw new Error('Error al abrir la caja: ' + data.message);
                    }
                    return data.cajaId;
                })
                .catch(error => {
                    console.error('Error al manejar la respuesta de la caja:', error);
                    throw error;
                });
        }

        function cerrarCaja(cajaId) {
            if (!cajaId) {
                console.error('No hay un ID de caja para cerrar.');
                return;
            }

            return fetch('/ventas/cerrarCaja', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        caja_id: cajaId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#inputCajaId').val('');
                        $('#botonCaja').text('Abrir Caja').removeClass('btn-warning').addClass('btn-primary');
                        console.log('Caja cerrada correctamente');
                    } else {
                        console.error('Error al cerrar la caja:', data.message);
                        throw new Error('Error al cerrar la caja: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al cerrar la caja:', error);
                    throw error;
                });
        }

        function toggleCaja() {

            const botonCaja = $('#toggleCajaBtn');  // Asegúrate de que el ID corresponde al de tu botón.
    const action = botonCaja.data('action');  // Usar .data() para acceder a los datos del atributo.
    const cajaId = $cajaAbierta

    botonCaja.prop('disabled', true);

            if (action === 'cerrar') { // Cerrar Caja

                @if (isset($cajaAbierta) && $cajaAbierta){
                    
                    
                cerrarCaja(cajaId).then(() => {
                    botonCaja.text('Abrir Caja').removeClass('btn-warning').addClass('btn-primary');
                    $('#inputCajaId').val(''); // Limpiar el ID de la caja cerrada
                    console.log('Caja cerrada correctamente');
                    botonCaja.prop('disabled', false);
                }).catch(error => {
                    console.error('Error al cerrar la caja:', error);
                    botonCaja.prop('disabled', false);
                });

                @endif

            }
            } else { // Abrir Caja
                abrirCaja().then(nuevaCajaId => {
                    botonCaja.text('Cerrar Caja').removeClass('btn-primary').addClass('btn-warning');
                    $('#inputCajaId').val(nuevaCajaId); // Establecer nuevo ID de caja
                    console.log('Caja abierta con ID:', nuevaCajaId);
                    botonCaja.prop('disabled', false);
                }).catch(error => {
                    console.error('Error al abrir la caja:', error);
                    botonCaja.prop('disabled', false);
                });
            }
        }



        $(document).ready(function() {
            $('.clickable-card').click(function(event) {
                event.preventDefault();
                const productId = $(this).data('product-id');
                const productName = $(this).data('product-name');
                const price = parseFloat($(this).data('product-price'));
                addToCart(productId, productName, price);
            });

            $('#modalCancelar').on('show.bs.modal', function() {
                actualizarCarrito();
            });

            $('#modalPago').on('show.bs.modal', function() {
                const cajaId = $('#inputCajaId').val();
                if (!cajaId) {
                    abrirCaja().then(cajaId => {
                        $('#inputCajaId').val(cajaId);
                    }).catch(error => {
                        console.error(error);
                    });
                }
            });

            const botonCaja = document.getElementById('botonCaja');
            if (botonCaja) {
                botonCaja.addEventListener('click', toggleCaja);
            }
            const cajaId = document.getElementById('cajaId');
            if (cajaId) {
                cajaId.addEventListener('click', toggleCaja);
            }
        });
    </script>
@endsection

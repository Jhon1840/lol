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
                    <input type="hidden" name="caja_id" id="inputCajaId"
                        value="{{ $cajaAbierta ? $cajaAbierta->id : '' }}">

                </form>
            </div>
        </div>
    </div>
</div>

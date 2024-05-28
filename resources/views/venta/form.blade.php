<?php
use App\Models\Product;
$products = Product::pluck('Nombre', 'id')->all();
$precios = Product::pluck('Precio_venta', 'id')->all(); // Agrega esta línea para obtener los precios de venta de los productos
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('fecha', 'Fecha') }}</label>
    <div>
        @if ($venta)
            <x-flat-picker name="fecha" :value="$venta->fecha" />
        @else
            <x-flat-picker name="fecha" />
        @endif

        {!! $errors->first('fecha', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Fecha de la venta.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label for="metodo_pago" class="form-label">Método de pago</label>
    <select id="metodo_pago" class="form-select" name="metodo_pago">
        <option value="efectivo">Efectivo</option>
        <option value="tarjeta">Tarjeta</option>
    </select>
    <div class="invalid-feedback">
        Por favor seleccione un método de pago.
    </div>
    <small class="form-hint">Seleccione cómo desea realizar el pago.</small>
</div>

<div id="pagoEfectivoDiv" class="form-group mb-3">
    <label for="dinero_recibido" class="form-label">Dinero Recibido</label>
    <input type="number" class="form-control" id="dinero_recibido" name="dinero_recibido"
        placeholder="Dinero recibido del cliente" required>
    <small class="form-hint">Ingrese el dinero recibido del cliente.</small>
</div>

<div class="form-group mb-3">
    <label for="cambio" class="form-label">Cambio</label>
    <input type="text" class="form-control" id="cambio" name="cambio" placeholder="Cambio a devolver" readonly>
    <small class="form-hint">Cambio a devolver al cliente.</small>
</div>

<!-- Inclusión de la vista parcial de la pasarela de pago -->
@include('venta.components.pasarela_tarjeta')

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('Nombre', 'Nombre') }}</label>
    <div>
        {{ Form::text('Nombre', $venta->cliente, ['class' => 'form-control' . ($errors->has('cliente') ? ' is-invalid' : ''), 'placeholder' => 'Nombre', 'required' => 'required']) }}
        {!! $errors->first('cliente', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Nombre del cliente.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('NIT', 'NIT') }}</label>
    <div>
        {{ Form::text('NIT', null, ['class' => 'form-control' . ($errors->has('NIT') ? ' is-invalid' : ''), 'placeholder' => 'NIT', 'required' => 'required']) }}
        {!! $errors->first('NIT', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Ingrese el NIT del cliente.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('CI', 'Cédula de Identidad') }}</label>
    <div>
        {{ Form::text('CI', null, ['class' => 'form-control' . ($errors->has('CI') ? ' is-invalid' : ''), 'placeholder' => 'CI', 'required' => 'required']) }}
        {!! $errors->first('CI', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Ingrese la cédula de identidad del cliente.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">Total del Carrito</label>
    <input type="text" class="form-control" id="inputTotalCarrito" name="total" readonly>
</div>

<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="#" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">Enviar</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#metodo_pago').change(function() {
            var metodoPago = $(this).val();
            if (metodoPago === 'efectivo') {
                $('#pagoEfectivoDiv').show();
                $('#pasarelaTarjeta').hide();
            } else if (metodoPago === 'tarjeta') {
                $('#pasarelaTarjeta').show();
                $('#pagoEfectivoDiv').hide();
            } else {
                $('#pagoEfectivoDiv').hide();
                $('#pasarelaTarjeta').hide();
            }
        });

        $('#dinero_recibido').on('input', calcularCambio);

        function calcularCambio() {
            var dineroRecibido = parseFloat($('#dinero_recibido').val()) || 0;
            var totalCarrito = parseFloat($('#inputTotalCarrito').val()) || 0;

            var cambio = dineroRecibido - totalCarrito;

            $('#cambio').val(cambio.toFixed(2));
        }

        $('#metodo_pago').trigger('change');
    });
</script>

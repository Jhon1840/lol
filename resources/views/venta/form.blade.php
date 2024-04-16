<?php
use App\Models\Product;
$products = Product::pluck('Nombre', 'id')->all();
$precios = Product::pluck('Precio_venta', 'id')->all(); // Agrega esta línea para obtener los precios de venta de los productos
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('fecha', 'Fecha') }}</label>
    <div>
        <x-flat-picker name="fecha" :value="$venta->fecha" />
        {!! $errors->first('fecha', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Fecha de la venta.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('total', 'Total') }}</label>
    <div>
        {{ Form::text('total', $venta->total, ['class' => 'form-control total', 'placeholder' => 'Total', 'readonly']) }}
        {!! $errors->first('total', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Total de la venta.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('cliente', 'Cliente') }}</label>
    <div>
        {{ Form::text('cliente', $venta->cliente, ['class' => 'form-control' . ($errors->has('cliente') ? ' is-invalid' : ''), 'placeholder' => 'Cliente']) }}
        {!! $errors->first('cliente', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Nombre del cliente.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('producto', 'Producto') }}</label>
    <div>
        <select id="producto" class="form-control{{ $errors->has('producto') ? ' is-invalid' : '' }}" name="producto">
            <option value="" selected disabled>Seleccione un producto</option>
            @foreach ($products as $id => $nombre)
                <option value="{{ $id }}" data-precio="{{ $precios[$id] }}">{{ $nombre }} - Precio:
                    {{ $precios[$id] }}</option>
            @endforeach
        </select>
        {!! $errors->first('producto', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Seleccione el producto.</small>
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('cantidad', 'Cantidad') }}</label>
    <div>
        {{ Form::number('cantidad', null, ['class' => 'form-control cantidad' . ($errors->has('cantidad') ? ' is-invalid' : ''), 'placeholder' => 'Cantidad']) }}
        {!! $errors->first('cantidad', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Ingrese la cantidad de productos.</small>
    </div>
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
        $('#producto, .cantidad').change(function() {
            var total = 0;

            $('#producto option:selected').each(function(index) {
                var cantidad = $('.cantidad').eq(index).val();
                var precio = $(this).data('precio');
                total += cantidad * precio;
            });

            $('.total').val(total.toFixed(2));
        });
    });
</script>

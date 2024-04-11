<?php
use App\Models\Product;
$products = Product::pluck('Nombre', 'id')->all();
$precios = Product::pluck('Precio_venta', 'id')->all(); // Agrega esta línea para obtener los precios de venta de los productos
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('cantidad', 'Cantidad') }}</label>
    <div>
        {{ Form::number('cantidad', null, ['class' => 'form-control' . ($errors->has('cantidad') ? ' is-invalid' : ''), 'placeholder' => 'Cantidad']) }}
        {!! $errors->first('cantidad', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">Ingrese la cantidad de productos.</small>
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
    <label class="form-label">Productos seleccionados:</label>
    <ul id="selectedProducts" class="list-group">
    </ul>
</div>

<script>
    $(document).ready(function() {
        $('#producto').change(function() {
            var selectedProduct = $('#producto option:selected').text();
            var selectedProductPrice = $('#producto option:selected').data(
                'precio'); // Obtén el precio del producto seleccionado
            $('#selectedProducts').append('<li class="list-group-item">' + selectedProduct);
        });
    });
</script>

<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('fecha') }}</label>
    <div>
        {{ Form::text('fecha', $venta->fecha, [
            'class' => 'form-control' . ($errors->has('fecha') ? ' is-invalid' : ''),
            'placeholder' => 'Fecha',
        ]) }}
        {!! $errors->first('fecha', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">venta <b>fecha</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('total') }}</label>
    <div>
        {{ Form::text('total', $venta->total, [
            'class' => 'form-control' . ($errors->has('total') ? ' is-invalid' : ''),
            'placeholder' => 'Total',
        ]) }}
        {!! $errors->first('total', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">venta <b>total</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('cliente') }}</label>
    <div>
        {{ Form::text('cliente', $venta->cliente, [
            'class' => 'form-control' . ($errors->has('cliente') ? ' is-invalid' : ''),
            'placeholder' => 'Cliente',
        ]) }}
        {!! $errors->first('cliente', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">venta <b>cliente</b> instruction.</small>
    </div>
</div>

<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="#" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">Submit</button>
        </div>
    </div>
</div>

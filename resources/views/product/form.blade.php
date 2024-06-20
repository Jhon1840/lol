<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('Nombre') }}</label>
    <div>
        {{ Form::text('Nombre', $product->Nombre, [
            'class' => 'form-control' . ($errors->has('Nombre') ? ' is-invalid' : ''),
            'placeholder' => 'Nombre',
        ]) }}
        {!! $errors->first('Nombre', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('Descripcion') }}</label>
    <div>
        {{ Form::text('Descripcion', $product->Descripcion, [
            'class' => 'form-control' . ($errors->has('Descripcion') ? ' is-invalid' : ''),
            'placeholder' => 'Descripcion',
        ]) }}
        {!! $errors->first('Descripcion', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('Proveedor') }}</label>
    <div>
        {{ Form::text('Proveedor', $product->Proveedor, [
            'class' => 'form-control' . ($errors->has('Proveedor') ? ' is-invalid' : ''),
            'placeholder' => 'Proveedor',
        ]) }}
        {!! $errors->first('Proveedor', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('stock') }}</label>
    <div>
        {{ Form::text('stock', $product->stock, [
            'class' => 'form-control' . ($errors->has('stock') ? ' is-invalid' : ''),
            'placeholder' => 'Stock',
            'id' => 'stock',
        ]) }}
        {!! $errors->first('stock', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('Precio_compra') }}</label>
    <div>
        {{ Form::text('Precio_compra', $product->Precio_compra, [
            'class' => 'form-control' . ($errors->has('Precio_compra') ? ' is-invalid' : ''),
            'placeholder' => 'Precio Compra',
            'id' => 'Precio_compra',
        ]) }}
        {!! $errors->first('Precio_compra', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label"> {{ Form::label('Precio_venta_(recomendado)') }}</label>
    <div>
        {{ Form::text('Precio_venta', $product->Precio_venta, [
            'class' => 'form-control' . ($errors->has('Precio_venta') ? ' is-invalid' : ''),
            'placeholder' => 'Precio Venta ',
            'id' => 'Precio_venta',
            'readonly' => true,
        ]) }}
        {!! $errors->first('Precio_venta', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="#" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">Crear</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function calculatePPP() {
            const stock = parseFloat(document.getElementById('stock').value) || 0;
            const precioCompra = parseFloat(document.getElementById('Precio_compra').value) || 0;
            let precioVenta = 0;

            if (stock > 0) {
                precioVenta = precioCompra / stock;
            }

            document.getElementById('Precio_venta').value = precioVenta.toFixed(2);
        }

        document.getElementById('stock').addEventListener('input', calculatePPP);
        document.getElementById('Precio_compra').addEventListener('input', calculatePPP);
    });
</script>

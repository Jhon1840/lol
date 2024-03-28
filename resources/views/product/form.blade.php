
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('Nombre') }}</label>
    <div>
        {{ Form::text('Nombre', $product->Nombre, ['class' => 'form-control' .
        ($errors->has('Nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
        {!! $errors->first('Nombre', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">product <b>Nombre</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('Descripcion') }}</label>
    <div>
        {{ Form::text('Descripcion', $product->Descripcion, ['class' => 'form-control' .
        ($errors->has('Descripcion') ? ' is-invalid' : ''), 'placeholder' => 'Descripcion']) }}
        {!! $errors->first('Descripcion', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">product <b>Descripcion</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('Proveedor') }}</label>
    <div>
        {{ Form::text('Proveedor', $product->Proveedor, ['class' => 'form-control' .
        ($errors->has('Proveedor') ? ' is-invalid' : ''), 'placeholder' => 'Proveedor']) }}
        {!! $errors->first('Proveedor', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">product <b>Proveedor</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('stock') }}</label>
    <div>
        {{ Form::text('stock', $product->stock, ['class' => 'form-control' .
        ($errors->has('stock') ? ' is-invalid' : ''), 'placeholder' => 'Stock']) }}
        {!! $errors->first('stock', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">product <b>stock</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('Precio_venta') }}</label>
    <div>
        {{ Form::text('Precio_venta', $product->Precio_venta, ['class' => 'form-control' .
        ($errors->has('Precio_venta') ? ' is-invalid' : ''), 'placeholder' => 'Precio Venta']) }}
        {!! $errors->first('Precio_venta', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">product <b>Precio_venta</b> instruction.</small>
    </div>
</div>
<div class="form-group mb-3">
    <label class="form-label">   {{ Form::label('Precio_compra') }}</label>
    <div>
        {{ Form::text('Precio_compra', $product->Precio_compra, ['class' => 'form-control' .
        ($errors->has('Precio_compra') ? ' is-invalid' : ''), 'placeholder' => 'Precio Compra']) }}
        {!! $errors->first('Precio_compra', '<div class="invalid-feedback">:message</div>') !!}
        <small class="form-hint">product <b>Precio_compra</b> instruction.</small>
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

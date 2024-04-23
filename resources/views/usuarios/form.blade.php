<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('name', 'Nombre') }}</label>
    <div>
        {{ Form::text('name', $user->name, [
            'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
            'placeholder' => 'Nombre',
        ]) }}
        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('email', 'Correo Electrónico') }}</label>
    <div>
        {{ Form::email('email', $user->email, [
            'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
            'placeholder' => 'Correo Electrónico',
        ]) }}
        {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label">{{ Form::label('password', 'Contraseña') }}</label>
    <div>
        {{ Form::password('password', [
            'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
            'placeholder' => 'Contraseña',
        ]) }}
        {!! $errors->first('password', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<!-- Resto del formulario ... -->

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Rol Actual del Usuario</h5>
        @if (empty($userRoles))
            <p class="card-text">El usuario no tiene roles asignados.</p>
        @else
            <ul class="list-group">
                @foreach ($userRoles as $roleId)
                    @php
                        $role = App\Models\Role::find($roleId);
                    @endphp
                    <li class="list-group-item">{{ $role->name }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>


<!-- Resto del formulario ... -->


<script>
    // Obtenemos todos los radio buttons dentro del dropdown-menu
    const radioButtons = document.querySelectorAll('.dropdown-menu input[type="radio"]');

    radioButtons.forEach((radioButton) => {
        // Agregamos un listener de evento change a cada radio button
        radioButton.addEventListener('change', function() {
            // Deseleccionamos los otros radio buttons
            radioButtons.forEach((otherRadioButton) => {
                if (otherRadioButton !== this) {
                    otherRadioButton.checked = false;
                }
            });
        });
    });
</script>




<div class="form-footer">
    <div class="text-end">
        <div class="d-flex">
            <a href="#" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary ms-auto ajax-submit">Actualizar</button>
        </div>
    </div>
</div>

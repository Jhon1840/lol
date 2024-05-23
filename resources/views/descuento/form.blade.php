@extends('tablar::page')

@section('title', 'Crear Descuento')

@section('content')
    <div class="container">
        <form action="{{ route('descuentos.store') }}" method="POST">
            @csrf
            <x-card>
                <x-slot:header>
                    <h3 class="card-title">Crear Descuento</h3>
                </x-slot:header>
                <x-slot:body>
                    <div class="form-group mb-3 position-relative">
                        <label class="form-label">Producto</label>
                        <div>
                            <input type="text" id="product_name" class="form-control" placeholder="Producto">
                            <input type="hidden" name="product_id" id="product_id">
                            {!! $errors->first('product_id', '<div class="invalid-feedback">:message</div>') !!}
                            <small class="form-hint"><b>Producto</b></small>
                            <div id="suggestion-box" class="list-group position-absolute w-100"
                                style="z-index: 1000; max-height: 150px; overflow-y: auto; background-color: #151f2c;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Descuento</label>
                        <div>
                            <input type="text" name="discount_percentage" class="form-control"
                                placeholder="Porcentaje de descuento">
                            {!! $errors->first('discount_percentage', '<div class="invalid-feedback">:message</div>') !!}
                            <small class="form-hint"><b>Descuento</b></small>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Fecha de inicio</label>
                        <div>
                            <input type="text" name="start_date" class="form-control flatpickr"
                                placeholder="Fecha Inicio" id="start_date">
                            {!! $errors->first('start_date', '<div class="invalid-feedback">:message</div>') !!}
                            <small class="form-hint"><b>Fecha de inicio</b></small>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Fecha de final</label>
                        <div>
                            <input type="text" name="end_date" class="form-control flatpickr" placeholder="Fecha Final"
                                id="end_date">
                            {!! $errors->first('end_date', '<div class="invalid-feedback">:message</div>') !!}
                            <small class="form-hint"><b>Fecha final</b></small>
                        </div>
                    </div>
                </x-slot:body>
                <x-slot:footer>
                    <a href="#" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-primary ms-auto ajax-submit">Submit</button>
                </x-slot:footer>
            </x-card>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const products = @json($products);
            const productInput = document.getElementById('product_name');
            const productIdInput = document.getElementById('product_id');

            productInput.addEventListener('input', function() {
                let currentValue = this.value.toLowerCase();
                let suggestions = products.filter(product => product.Nombre.toLowerCase().includes(
                    currentValue));

                let suggestionBox = document.getElementById('suggestion-box');
                suggestionBox.innerHTML = '';
                suggestions.forEach(product => {
                    let suggestionItem = document.createElement('a');
                    suggestionItem.className = 'list-group-item list-group-item-action p-1';
                    suggestionItem.style.backgroundColor = '#151f2c';
                    suggestionItem.style.color = '#ffffff';
                    suggestionItem.textContent = product.Nombre;
                    suggestionItem.style.cursor = 'pointer';
                    suggestionItem.addEventListener('click', function() {
                        productInput.value = product.Nombre;
                        productIdInput.value = product.id;
                        suggestionBox.innerHTML = '';
                    });
                    suggestionBox.appendChild(suggestionItem);
                });
            });

            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                locale: {
                    firstDayOfWeek: 1 // Start week on Monday
                },
                onChange: function(selectedDates, dateStr, instance) {
                    const startDateInput = document.getElementById('start_date');
                    const endDateInput = document.getElementById('end_date');
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (startDate && endDate && endDate < startDate) {
                        endDateInput.setCustomValidity(
                            'La fecha de final no puede ser anterior a la fecha de inicio.');
                    } else {
                        endDateInput.setCustomValidity('');
                    }
                }
            });
        });
    </script>
@endsection

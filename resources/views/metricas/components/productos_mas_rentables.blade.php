<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Productos Más Rentables</h3>
        </div>
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Total Generado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productosMasRentables as $producto)
                <tr>
                    <td>{{ $producto->Nombre }}</td>
                    <td>${{ number_format($producto->total_generado, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- resources/views/metricas/components/productos_menor_stock.blade.php -->

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Productos con Menor Stock</h3>
        </div>
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productosMenorStock as $producto)
                    <tr>
                        <td>{{ $producto->Nombre }}</td>
                        <td>{{ $producto->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


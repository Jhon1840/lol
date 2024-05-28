<div class="col-md-8">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Productos Más Vendidos</h3>
        </div>
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad Vendida</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productosMasVendidos as $producto)
                    <tr>
                        <td>{{ $producto->Nombre }}</td>
                        <td>{{ $producto->total_vendido }}</td>
                        <td class="w-50">
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-primary"
                                    style="width: {{ ($producto->total_vendido / $maxVendidos) * 100 }}%">
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

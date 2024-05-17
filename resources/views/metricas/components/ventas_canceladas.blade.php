<!-- resources/views/metricas/components/ventas_canceladas.blade.php -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dinero de Ventas Canceladas</h3>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ventasCanceladas as $venta)
                    <tr>
                        <td class="text-nowrap text-secondary">{{ $venta->fecha }}</td>
                        <td class="text-nowrap">{{ $venta->total }}</td>
                        <td class="text-nowrap">{{ $venta->cliente }}</td>
                        <td class="text-nowrap">{{ $venta->vendedor }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-nowrap text-secondary font-weight-bold">Total Dinero Devuelto</td>
                    <td class="text-nowrap font-weight-bold">{{ $totalDineroDevuelto }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

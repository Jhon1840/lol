@extends('tablar::page')

@section('title', 'Facturas')

@section('content')
    <div class="container">
        <h1>Facturas</h1>
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>ID Venta</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                        <tr>
                            <td>{{ $factura['id'] }}</td>
                            <td>{{ $factura['fecha'] }}</td>
                            <td>{{ $factura['cliente'] }}</td>
                            <td>{{ $factura['total'] }}</td>
                            <td>
                                <a href="{{ $factura['url'] }}" class="btn btn-primary btn-sm" target="_blank">Ver PDF</a>
                                <a href="{{ $factura['url'] }}" class="btn btn-secondary btn-sm"
                                    download="{{ $factura['nombre_archivo'] }}">Descargar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay facturas disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
